<x-layouts.app title="Profiles">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-medium text-teal-800">Profile CRUD</p>
                <h1 class="mt-1 text-3xl font-semibold text-slate-950">Student profiles</h1>
            </div>
            @if (! auth()->user()->isParent())
                <a href="{{ route('profiles.create') }}" class="rounded-md bg-teal-950 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-teal-900">Add profile</a>
            @endif
        </div>

        <form class="mt-6">
            <input name="search" value="{{ request('search') }}" placeholder="Search name, student number, grade" class="w-full rounded-md border border-slate-300 px-4 py-3 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15">
        </form>

        <div class="mt-6 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Student</th>
                            <th class="px-5 py-3">Grade</th>
                            @if (! auth()->user()->isAdmin())
                                <th class="px-5 py-3">Blood type</th>
                            @endif
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($profiles as $student)
                            <tr>
                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-950">{{ $student->last_name }}, {{ $student->first_name }}</p>
                                    <p class="mt-1 text-slate-500">{{ $student->student_number }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ $student->grade_level ?? 'N/A' }} {{ $student->section }}</td>
                                @if (! auth()->user()->isAdmin())
                                    <td class="px-5 py-4 text-slate-600">{{ $student->healthProfile?->blood_type ?? 'N/A' }}</td>
                                @endif
                                <td class="px-5 py-4">
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $student->status }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('profiles.show', $student) }}" class="font-semibold text-teal-800 hover:text-teal-950">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">No profiles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-4">
                {{ $profiles->links() }}
            </div>
        </div>
    </section>
</x-layouts.app>
