<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClinicVisit;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClinicVisitController extends Controller
{
    public function index(Student $student, Request $request): JsonResponse
    {
        $visits = $student->clinicVisits()
            ->latest('visited_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($visits);
    }

    public function store(Student $student, Request $request): JsonResponse
    {
        $visit = $student->clinicVisits()->create($this->validateVisit($request));

        return response()->json($visit, 201);
    }

    public function show(ClinicVisit $clinicVisit): JsonResponse
    {
        return response()->json($clinicVisit->load('student'));
    }

    public function update(Request $request, ClinicVisit $clinicVisit): JsonResponse
    {
        $clinicVisit->update($this->validateVisit($request));

        return response()->json($clinicVisit->refresh());
    }

    public function destroy(ClinicVisit $clinicVisit): JsonResponse
    {
        $clinicVisit->delete();

        return response()->json(status: 204);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateVisit(Request $request): array
    {
        return $request->validate([
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
        ]);
    }
}
