<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HealthRecordController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->isClinicStaff(), 403);

        $students = Student::query()
            ->with(['healthProfile', 'clinicVisits', 'bmiRecords', 'dentalRecords'])
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('student_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('health-records.index', compact('students'));
    }
}
