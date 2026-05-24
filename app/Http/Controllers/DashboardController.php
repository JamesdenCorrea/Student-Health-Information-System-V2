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
        $user = auth()->user();
        $studentQuery = Student::query();

        if ($user->isParent()) {
            $studentQuery->whereHas('parents', fn ($query) => $query->whereKey($user->id));
        }

        $studentIds = (clone $studentQuery)->pluck('id');

        return view('dashboard', [
            'studentCount' => (clone $studentQuery)->count(),
            'profileCount' => $user->isAdmin() ? null : HealthProfile::whereIn('student_id', $studentIds)->count(),
            'visitCount' => $user->isAdmin() ? null : ClinicVisit::whereIn('student_id', $studentIds)->count(),
            'recentStudents' => (clone $studentQuery)
                ->with('healthProfile')
                ->latest()
                ->limit(5)
                ->get(),
            'recentVisits' => $user->isAdmin() ? collect() : ClinicVisit::query()
                ->whereIn('student_id', $studentIds)
                ->with('student')
                ->latest('visited_at')
                ->limit(5)
                ->get(),
        ]);
    }
}
