<?php

namespace App\Http\Controllers;

use App\Models\BmiRecord;
use App\Models\ClinicVisit;
use App\Models\DentalRecord;
use App\Models\HealthProfile;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $studentQuery = Student::query();

        if ($user->isParent()) {
            $studentQuery->whereHas('parents', fn ($query) => $query->whereKey($user->id));
        }

        $studentIds = (clone $studentQuery)->pluck('id');

        return view('analytics.index', [
            'studentCount' => (clone $studentQuery)->count(),
            'activeCount' => (clone $studentQuery)->where('status', 'active')->count(),
            'healthProfileCount' => $user->isAdmin() ? null : HealthProfile::whereIn('student_id', $studentIds)->count(),
            'clinicVisitCount' => $user->isAdmin() ? null : ClinicVisit::whereIn('student_id', $studentIds)->count(),
            'dentalConcernCount' => $user->isAdmin() ? null : DentalRecord::whereIn('student_id', $studentIds)->whereIn('condition', ['decayed', 'missing', 'removed'])->count(),
            'latestBmi' => $user->isAdmin() ? collect() : BmiRecord::query()
                ->whereIn('student_id', $studentIds)
                ->with('student')
                ->latest('checked_at')
                ->limit(8)
                ->get(),
        ]);
    }
}
