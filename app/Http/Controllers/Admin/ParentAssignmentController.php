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
    public function index(Request $request): View
    {
        $selectedParent = User::query()
            ->where('role', User::ROLE_PARENT)
            ->with('children')
            ->find($request->integer('parent_id') ?: $request->integer('user_id'));

        $selectedStudent = Student::query()
            ->where('student_number', $request->query('student_number'))
            ->first();

        return view('admin.parent-assignments.index', [
            'parents' => User::query()
                ->where('role', User::ROLE_PARENT)
                ->with('children')
                ->orderBy('name')
                ->get(),
            'students' => Student::query()->orderBy('last_name')->orderBy('first_name')->get(),
            'selectedParent' => $selectedParent,
            'selectedStudent' => $selectedStudent,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')->where('role', User::ROLE_PARENT)],
            'student_id' => ['required', 'exists:students,id'],
            'student_number' => ['nullable', 'exists:students,student_number'],
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
