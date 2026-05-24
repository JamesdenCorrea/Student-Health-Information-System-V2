<x-layouts.app title="Dashboard">
    <section class="bg-gradient-to-r from-teal-950 via-teal-900 to-emerald-700 text-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="text-sm font-medium text-teal-50/80">Dashboard</p>
            <div class="mt-2 flex flex-col justify-between gap-4 md:flex-row md:items-end">
                <div>
                    <h1 class="text-3xl font-semibold">Welcome, {{ auth()->user()->name }}</h1>
                    <p class="mt-2 text-teal-50/80">Role: <span class="font-semibold text-white">{{ str_replace('_', ' ', auth()->user()->role) }}</span></p>
                </div>
                <a href="{{ route('profiles.create') }}" class="w-fit rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-teal-950 shadow-sm hover:bg-teal-50">New profile</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Student profiles</p>
                <p class="mt-3 text-3xl font-semibold">{{ $studentCount }}</p>
            </div>
            @if (! auth()->user()->isAdmin())
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Health records</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $profileCount }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Clinic visits</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $visitCount }}</p>
                </div>
            @else
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm md:col-span-2">
                    <p class="text-sm font-medium text-slate-500">Admin access</p>
                    <p class="mt-3 text-sm leading-6 text-slate-700">Health records are restricted to clinic staff. Admin users manage users, students, parent assignments, and non-health analytics.</p>
                </div>
            @endif
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            @if (! auth()->user()->isAdmin())
            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold">Recent profiles</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentStudents as $student)
                        <a href="{{ route('profiles.show', $student) }}" class="block px-5 py-4 hover:bg-teal-50/60">
                            <p class="font-medium">{{ $student->last_name }}, {{ $student->first_name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $student->student_number }} · Grade {{ $student->grade_level ?? 'N/A' }}</p>
                        </a>
                    @empty
                        <p class="px-5 py-6 text-sm text-slate-500">No profiles yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold">Recent clinic visits</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentVisits as $visit)
                        <div class="px-5 py-4">
                            <p class="font-medium">{{ $visit->complaint }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $visit->student?->first_name }} {{ $visit->student?->last_name }} · {{ $visit->visited_at?->format('M d, Y h:i A') }}</p>
                        </div>
                    @empty
                        <p class="px-5 py-6 text-sm text-slate-500">No clinic visits yet.</p>
                    @endforelse
                </div>
            </div>
            @endif
        </div>
    </section>
</x-layouts.app>
