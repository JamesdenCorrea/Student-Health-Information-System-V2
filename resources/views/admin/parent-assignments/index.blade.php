<x-layouts.app title="Parent Assignments">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">Admin</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Assign parents to students</h1>
        </div>

        <form method="POST" action="{{ route('admin.parent-assignments.store') }}" class="grid gap-4 rounded-lg border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-4">
            @csrf
            <select name="user_id" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                <option value="">Parent user</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }} · {{ $parent->email }}</option>
                @endforeach
            </select>
            <select name="student_id" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                <option value="">Student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->last_name }}, {{ $student->first_name }} · {{ $student->student_number }}</option>
                @endforeach
            </select>
            <input name="relationship" placeholder="Relationship" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            <button class="rounded-md bg-teal-950 px-4 py-2.5 text-sm font-semibold text-white">Assign</button>
        </form>

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            @foreach ($parents as $parent)
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-950">{{ $parent->name }}</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $parent->email }}</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($parent->children as $student)
                            <div class="flex items-center justify-between gap-3 rounded-md bg-slate-50 px-3 py-2 text-sm">
                                <span>{{ $student->last_name }}, {{ $student->first_name }} <span class="text-slate-500">({{ $student->pivot->relationship ?? 'parent' }})</span></span>
                                <form method="POST" action="{{ route('admin.parent-assignments.destroy', [$parent, $student]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-red-700">Remove</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No assigned students.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.app>
