<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $hasSearch = filled($request->query('search'));

        $profiles = Student::query()
            ->with('healthProfile')
            ->when($user->isParent(), fn ($query) => $query->whereHas('parents', fn ($query) => $query->whereKey($user->id)))
            ->when($user->isClinicStaff() && ! $hasSearch, fn ($query) => $query->whereRaw('1 = 0'))
            ->when($request->query('search'), function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('student_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('grade_level', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('profiles.index', compact('profiles'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->isAdmin() || auth()->user()->isClinicStaff(), 403);

        return view('profiles.create', ['student' => new Student]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isClinicStaff(), 403);

        $validated = $this->validateProfile($request);
        $healthProfile = $request->user()->isClinicStaff()
            ? $this->normalizeHealthProfile($validated['health_profile'] ?? [])
            : [];

        $student = DB::transaction(function () use ($validated, $healthProfile): Student {
            unset($validated['health_profile']);

            if (request()->hasFile('photo')) {
                $validated['photo_path'] = request()->file('photo')->store('student-photos', 'public');
            }

            $student = Student::create($validated);
            if ($healthProfile !== []) {
                $student->healthProfile()->create($healthProfile);
            }

            return $student;
        });

        return redirect()
            ->route('profiles.show', $student)
            ->with('status', 'Profile created successfully.');
    }

    public function show(Student $profile): View
    {
        abort_unless(request()->user()->canViewStudent($profile), 403);

        return view('profiles.show', [
            'student' => $profile->load([
                'healthProfile',
                'clinicVisits' => fn ($query) => $query->latest('visited_at'),
                'bmiRecords' => fn ($query) => $query->latest('checked_at'),
                'dentalRecords' => fn ($query) => $query->latest('recorded_at'),
                'parents',
            ]),
        ]);
    }

    public function edit(Student $profile): View
    {
        abort_unless(auth()->user()->isAdmin() || auth()->user()->isClinicStaff(), 403);

        return view('profiles.edit', ['student' => $profile->load('healthProfile')]);
    }

    public function update(Request $request, Student $profile): RedirectResponse
    {
        abort_unless($request->user()->isAdmin() || $request->user()->isClinicStaff(), 403);

        $validated = $this->validateProfile($request, $profile);
        $healthProfile = $request->user()->isClinicStaff()
            ? $this->normalizeHealthProfile($validated['health_profile'] ?? [])
            : null;

        DB::transaction(function () use ($request, $profile, $validated, $healthProfile): void {
            unset($validated['health_profile']);

            if ($request->hasFile('photo')) {
                if ($profile->photo_path) {
                    Storage::disk('public')->delete($profile->photo_path);
                }

                $validated['photo_path'] = $request->file('photo')->store('student-photos', 'public');
            }

            $profile->update($validated);
            if ($healthProfile !== null) {
                $profile->healthProfile()->updateOrCreate(
                    ['student_id' => $profile->id],
                    $healthProfile,
                );
            }
        });

        return redirect()
            ->route('profiles.show', $profile)
            ->with('status', 'Profile updated successfully.');
    }

    public function destroy(Student $profile): RedirectResponse
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $profile->delete();

        return redirect()
            ->route('profiles.index')
            ->with('status', 'Profile deleted successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProfile(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'student_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('students', 'student_number')->ignore($student),
            ],
            'photo' => ['nullable', 'image', 'max:2048'],
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
            'status' => ['required', 'string', 'max:50'],
            'health_profile' => [$request->user()->isClinicStaff() ? 'sometimes' : 'prohibited', 'array'],
            'health_profile.blood_type' => ['nullable', 'string', 'max:10'],
            'health_profile.allergies' => ['nullable', 'string'],
            'health_profile.chronic_conditions' => ['nullable', 'string'],
            'health_profile.medications' => ['nullable', 'string'],
            'health_profile.immunizations' => ['nullable', 'string'],
            'health_profile.physician_name' => ['nullable', 'string', 'max:255'],
            'health_profile.physician_contact' => ['nullable', 'string', 'max:255'],
            'health_profile.notes' => ['nullable', 'string'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $profile
     * @return array<string, mixed>
     */
    private function normalizeHealthProfile(array $profile): array
    {
        foreach (['allergies', 'chronic_conditions', 'medications', 'immunizations'] as $field) {
            $profile[$field] = collect(explode(',', (string) ($profile[$field] ?? '')))
                ->map(fn (string $item): string => trim($item))
                ->filter()
                ->values()
                ->all();
        }

        return $profile;
    }
}
