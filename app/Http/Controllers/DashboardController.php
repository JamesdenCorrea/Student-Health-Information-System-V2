<?php

namespace App\Http\Controllers;

use App\Models\ClinicVisit;
use App\Models\HealthProfile;
use App\Models\Student;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'studentCount' => Student::count(),
            'profileCount' => HealthProfile::count(),
            'visitCount' => ClinicVisit::count(),
            'recentStudents' => Student::query()
                ->with('healthProfile')
                ->latest()
                ->limit(5)
                ->get(),
            'recentVisits' => ClinicVisit::query()
                ->with('student')
                ->latest('visited_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
