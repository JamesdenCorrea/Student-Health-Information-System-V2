<?php

namespace App\Http\Controllers;

use App\Models\ClinicVisit;
use App\Models\SmsNotification;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClinicVisitWebController extends Controller
{
    public function create(Student $student): View
    {
        abort_unless(request()->user()->isClinicStaff(), 403);

        return view('clinic-visits.create', compact('student'));
    }

    public function store(Request $request, Student $student): RedirectResponse
    {
        abort_unless($request->user()->isClinicStaff(), 403);

        $validated = $request->validate([
            'visited_at' => ['required', 'date'],
            'complaint' => ['required', 'string'],
            'assessment' => ['nullable', 'string'],
            'treatment' => ['nullable', 'string'],
            'temperature' => ['nullable', 'numeric', 'between:30,45'],
            'blood_pressure' => ['nullable', 'string', 'max:50'],
            'pulse_rate' => ['nullable', 'integer', 'between:20,250'],
            'nurse_name' => ['nullable', 'string', 'max:255'],
            'disposition' => ['nullable', 'string', 'max:255'],
            'follow_up_at' => ['nullable', 'date', 'after_or_equal:visited_at'],
            'notify_parent' => ['nullable', 'boolean'],
        ]);

        $visit = $student->clinicVisits()->create(collect($validated)->except('notify_parent')->all());

        if (($validated['disposition'] ?? null) === ClinicVisit::DISPOSITION_SENT_HOME && $request->boolean('notify_parent')) {
            $recipientPhone = $student->guardian_contact ?: $student->emergency_contact_phone;

            if ($recipientPhone) {
                SmsNotification::create([
                    'student_id' => $student->id,
                    'clinic_visit_id' => $visit->id,
                    'sent_by' => $request->user()->id,
                    'recipient_name' => $student->guardian_name ?: $student->emergency_contact_name,
                    'recipient_phone' => $recipientPhone,
                    'message' => "{$student->first_name} {$student->last_name} was sent home due to illness. Complaint: {$visit->complaint}",
                    'status' => 'queued',
                ]);
            }
        }

        return redirect()->route('profiles.show', $student)->with('status', 'Clinic visit recorded.');
    }
}
