<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ParentAssignmentController extends Controller
{
    public function index(): View
    {
        return view('admin.parent-assignments.index', [
            'parents' => User::query()
                ->where('role', User::ROLE_PARENT)
                ->with('children')
                ->orderBy('name')
                ->get(),
            'students' => Student::query()->orderBy('last_name')->orderBy('first_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', User::ROLE_PARENT)],
            'student_id' => ['required', 'exists:students,id'],
            'relationship' => ['nullable', 'string', 'max:50'],
        ]);

        $parent = User::findOrFail($validated['user_id']);
        $parent->children()->syncWithoutDetaching([
            $validated['student_id'] => ['relationship' => $validated['relationship'] ?? null],
        ]);

        return back()->with('status', 'Parent assigned to student.');
    }

    public function destroy(User $user, Student $student): RedirectResponse
    {
        abort_unless($user->isParent(), 404);

        $user->children()->detach($student->id);

        return back()->with('status', 'Parent assignment removed.');
    }
}
