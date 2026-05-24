<x-layouts.app title="Parent Assignments">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">Admin</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Assign parents to students</h1>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_380px]">
            <form method="POST" action="{{ route('admin.parent-assignments.store') }}" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                @csrf
                <h2 class="font-semibold text-slate-950">Match parent and student</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="parent_search">Parent</label>
                        <input id="parent_search" list="parent_options" value="{{ $selectedParent ? $selectedParent->name.' · '.$selectedParent->email : '' }}" placeholder="Search parent name or email" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm">
                        <input id="user_id" name="user_id" type="hidden" value="{{ $selectedParent?->id }}">
                        <datalist id="parent_options">
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->name }} · {{ $parent->email }}" data-id="{{ $parent->id }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="student_number">Student ID</label>
                        <input id="student_number" name="student_number" list="student_options" value="{{ $selectedStudent?->student_number }}" placeholder="Type student ID" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm">
                        <input id="student_id" name="student_id" type="hidden" value="{{ $selectedStudent?->id }}">
                        <datalist id="student_options">
                            @foreach ($students as $student)
                                <option value="{{ $student->student_number }}" data-id="{{ $student->id }}" data-name="{{ $student->last_name }}, {{ $student->first_name }}" data-grade="Grade {{ $student->grade_level ?? 'N/A' }} {{ $student->section }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="student_preview">Student match</label>
                        <input id="student_preview" value="{{ $selectedStudent ? $selectedStudent->last_name.', '.$selectedStudent->first_name.' · Grade '.$selectedStudent->grade_level.' '.$selectedStudent->section : '' }}" placeholder="Auto-filled after valid student ID" class="mt-2 w-full rounded-md border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm" readonly>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="relationship">Relationship</label>
                        <input id="relationship" name="relationship" value="Parent" class="mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm">
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-3">
                    <button formaction="{{ route('admin.parent-assignments.index') }}" formmethod="GET" class="rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Preview</button>
                    <button class="rounded-md bg-teal-950 px-4 py-2.5 text-sm font-semibold text-white">Assign</button>
                </div>
            </form>

            <aside class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-950">Assigned children</h2>
                @if ($selectedParent)
                    <p class="mt-1 text-sm text-slate-500">{{ $selectedParent->name }}</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($selectedParent->children as $student)
                            <div class="flex items-center justify-between gap-3 rounded-md bg-slate-50 px-3 py-2 text-sm">
                                <span>{{ $student->last_name }}, {{ $student->first_name }} <span class="text-slate-500">({{ $student->pivot->relationship ?? 'Parent' }})</span></span>
                                <form method="POST" action="{{ route('admin.parent-assignments.destroy', [$selectedParent, $student]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-red-700">Remove</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No assigned students.</p>
                        @endforelse
                    </div>
                @else
                    <p class="mt-4 text-sm text-slate-500">Select a parent and use Preview to see assigned students.</p>
                @endif
            </aside>
        </div>
    </section>

    <script>
        const parentInput = document.getElementById('parent_search');
        const parentId = document.getElementById('user_id');
        const parentOptions = [...document.querySelectorAll('#parent_options option')];
        const studentNumber = document.getElementById('student_number');
        const studentId = document.getElementById('student_id');
        const studentPreview = document.getElementById('student_preview');
        const studentOptions = [...document.querySelectorAll('#student_options option')];

        parentInput.addEventListener('input', () => {
            const match = parentOptions.find(option => option.value === parentInput.value);
            parentId.value = match ? match.dataset.id : '';
        });

        studentNumber.addEventListener('input', () => {
            const match = studentOptions.find(option => option.value === studentNumber.value);
            studentId.value = match ? match.dataset.id : '';
            studentPreview.value = match ? `${match.dataset.name} · ${match.dataset.grade}` : '';
        });
    </script>
</x-layouts.app>
