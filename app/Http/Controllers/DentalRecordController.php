<?php

namespace App\Http\Controllers;

use App\Models\DentalRecord;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DentalRecordController extends Controller
{
    public function edit(Student $student): View
    {
        abort_unless(request()->user()->isClinicStaff(), 403);

        return view('dental-records.edit', [
            'student' => $student->load('dentalRecords'),
            'conditions' => DentalRecord::CONDITIONS,
            'teeth' => $this->teeth(),
        ]);
    }

    public function store(Request $request, Student $student): RedirectResponse
    {
        abort_unless($request->user()->isClinicStaff(), 403);

        $validated = $request->validate([
            'tooth_code' => ['required', 'string', 'max:10'],
            'condition' => ['required', Rule::in(DentalRecord::CONDITIONS)],
            'notes' => ['nullable', 'string'],
            'recorded_at' => ['required', 'date'],
        ]);

        $student->dentalRecords()->create([
            ...$validated,
            'recorded_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Dental record updated.');
    }

    /**
     * @return array<int, string>
     */
    private function teeth(): array
    {
        return [
            '18', '17', '16', '15', '14', '13', '12', '11',
            '21', '22', '23', '24', '25', '26', '27', '28',
            '48', '47', '46', '45', '44', '43', '42', '41',
            '31', '32', '33', '34', '35', '36', '37', '38',
        ];
    }
}
