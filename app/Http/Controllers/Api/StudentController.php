<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $students = Student::query()
            ->with('healthProfile')
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('student_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->when($request->query('status'), fn ($query, string $status) => $query->where('status', $status))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json($students);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateStudent($request);

        $student = DB::transaction(function () use ($validated): Student {
            $profile = $validated['health_profile'] ?? [];
            unset($validated['health_profile']);

            $student = Student::create($validated);
            $student->healthProfile()->create($profile);

            return $student->load('healthProfile');
        });

        return response()->json($student, 201);
    }

    public function show(Student $student): JsonResponse
    {
        return response()->json($student->load(['healthProfile', 'clinicVisits' => fn ($query) => $query->latest('visited_at')]));
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $this->validateStudent($request, $student);

        $student = DB::transaction(function () use ($student, $validated): Student {
            $profile = $validated['health_profile'] ?? null;
            unset($validated['health_profile']);

            $student->update($validated);

            if ($profile !== null) {
                $student->healthProfile()->updateOrCreate(
                    ['student_id' => $student->id],
                    $profile,
                );
            }

            return $student->load('healthProfile');
        });

        return response()->json($student);
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json(status: 204);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateStudent(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'student_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_number')->ignore($student),
            ],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'sex' => ['nullable', 'string', 'max:20'],
            'grade_level' => ['nullable', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_contact' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'max:50'],
            'health_profile' => ['sometimes', 'array'],
            'health_profile.blood_type' => ['nullable', 'string', 'max:10'],
            'health_profile.allergies' => ['nullable', 'array'],
            'health_profile.chronic_conditions' => ['nullable', 'array'],
            'health_profile.medications' => ['nullable', 'array'],
            'health_profile.immunizations' => ['nullable', 'array'],
            'health_profile.physician_name' => ['nullable', 'string', 'max:255'],
            'health_profile.physician_contact' => ['nullable', 'string', 'max:255'],
            'health_profile.notes' => ['nullable', 'string'],
        ]);
    }
}
