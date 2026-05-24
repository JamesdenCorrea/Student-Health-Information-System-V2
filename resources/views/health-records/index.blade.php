<x-layouts.app title="Health Records">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">Clinic staff</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Student health records</h1>
        </div>

        <form class="mb-5 flex gap-3 rounded-lg border border-slate-200 bg-white p-3 shadow-sm">
            <input name="search" value="{{ request('search') }}" placeholder="Search student number, first name, or last name" class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
            <button class="rounded-md bg-teal-950 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-teal-900">Search</button>
        </form>

        <div class="space-y-3">
            @forelse ($students as $student)
                <details class="sh-panel sh-panel-hover rounded-lg">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-5 py-4">
                        <span>
                            <span class="font-semibold text-slate-950">{{ $student->last_name }}, {{ $student->first_name }}</span>
                            <span class="ml-2 text-sm text-slate-500">{{ $student->student_number }}</span>
                        </span>
                        <span class="grid size-8 place-items-center rounded-full bg-teal-50 text-lg font-semibold text-teal-900">+</span>
                    </summary>
                    <div class="grid gap-4 border-t border-slate-100 p-5 md:grid-cols-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Blood type</p>
                            <p class="mt-1 text-sm">{{ $student->healthProfile?->blood_type ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Allergies</p>
                            <p class="mt-1 text-sm">{{ implode(', ', $student->healthProfile?->allergies ?? []) ?: 'None listed' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Clinic visits</p>
                            <p class="mt-1 text-sm">{{ $student->clinicVisits->count() }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('profiles.show', $student) }}" class="rounded-md bg-teal-950 px-3 py-2 text-sm font-semibold text-white">Open</a>
                            <a href="{{ route('dental-records.edit', $student) }}" class="rounded-md border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700">Dental</a>
                        </div>
                    </div>
                </details>
            @empty
                <div class="sh-panel rounded-lg p-8 text-center text-sm text-slate-500">
                    {{ $hasSearch ? 'No matching health record found.' : 'Search a student first to display health records.' }}
                </div>
            @endforelse
        </div>

        <div class="mt-5">{{ $students->links() }}</div>
    </section>
</x-layouts.app>
