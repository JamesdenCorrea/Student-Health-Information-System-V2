<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BmiRecordController extends Controller
{
    public function create(Student $student): View
    {
        abort_unless(request()->user()->isClinicStaff(), 403);

        return view('bmi-records.create', compact('student'));
    }

    public function store(Request $request, Student $student): RedirectResponse
    {
        abort_unless($request->user()->isClinicStaff(), 403);

        $validated = $request->validate([
            'school_year' => ['required', 'integer', 'between:2000,2100'],
            'height_cm' => ['required', 'numeric', 'between:30,250'],
            'weight_kg' => ['required', 'numeric', 'between:1,300'],
            'checked_at' => ['required', 'date'],
        ]);

        $heightMeters = ((float) $validated['height_cm']) / 100;
        $bmi = round(((float) $validated['weight_kg']) / ($heightMeters * $heightMeters), 2);

        $student->bmiRecords()->updateOrCreate(
            ['school_year' => $validated['school_year']],
            [
                ...$validated,
                'recorded_by' => $request->user()->id,
                'bmi' => $bmi,
                'category' => $this->category($bmi),
            ],
        );

        return redirect()->route('profiles.show', $student)->with('status', 'BMI record saved.');
    }

    private function category(float $bmi): string
    {
        return match (true) {
            $bmi < 18.5 => 'Underweight',
            $bmi < 25 => 'Normal',
            $bmi < 30 => 'Overweight',
            default => 'Obese',
        };
    }
}
