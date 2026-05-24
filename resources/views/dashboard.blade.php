<x-layouts.app title="Dashboard">
    <section class="border-b border-teal-950/10 bg-gradient-to-r from-teal-950 via-teal-900 to-emerald-700 text-white">
        <div class="mx-auto max-w-7xl px-4 py-9 sm:px-6 lg:px-8">
            <p class="text-sm font-medium text-teal-50/80">Dashboard</p>
            <div class="mt-2 flex flex-col justify-between gap-5 md:flex-row md:items-end">
                <div>
                    <h1 class="text-3xl font-semibold">Welcome, {{ auth()->user()->name }}</h1>
                    <p class="mt-2 text-teal-50/80">Role: <span class="font-semibold capitalize text-white">{{ str_replace('_', ' ', auth()->user()->role) }}</span></p>
                </div>
                @if (! auth()->user()->isParent())
                    <a href="{{ route('profiles.create') }}" class="w-fit rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-teal-950 shadow-sm hover:bg-teal-50">New profile</a>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid gap-4 md:grid-cols-3">
            <div class="sh-panel sh-panel-hover rounded-lg p-5">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">Student profiles</p>
                    <span class="grid size-9 place-items-center rounded-md bg-teal-50 text-teal-900">ID</span>
                </div>
                <p class="mt-3 text-3xl font-semibold text-slate-950">{{ $studentCount }}</p>
            </div>
            @if (! auth()->user()->isAdmin())
                <div class="sh-panel sh-panel-hover rounded-lg p-5">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm font-medium text-slate-500">Health records</p>
                        <span class="grid size-9 place-items-center rounded-md bg-emerald-50 text-emerald-900">+</span>
                    </div>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">{{ $profileCount }}</p>
                </div>
                <div class="sh-panel sh-panel-hover rounded-lg p-5">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm font-medium text-slate-500">Clinic visits</p>
                        <span class="grid size-9 place-items-center rounded-md bg-sky-50 text-sky-900">CV</span>
                    </div>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">{{ $visitCount }}</p>
                </div>
            @else
                <div class="sh-panel rounded-lg p-5 md:col-span-2">
                    <p class="text-sm font-medium text-slate-500">Admin access</p>
                    <p class="mt-3 text-sm leading-6 text-slate-700">Health records are restricted to clinic staff. Admin users manage users, students, parent assignments, and non-health analytics.</p>
                </div>
            @endif
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-2">
            <div class="sh-panel overflow-hidden rounded-lg">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold">Recent profiles</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentStudents as $student)
                        <a href="{{ route('profiles.show', $student) }}" class="flex items-center gap-3 px-5 py-4 hover:bg-teal-50/60">
                            <span class="grid size-10 shrink-0 place-items-center rounded-md bg-slate-100 text-xs font-semibold text-slate-700">{{ str($student->first_name)->substr(0, 1) }}{{ str($student->last_name)->substr(0, 1) }}</span>
                            <span>
                                <span class="block font-medium">{{ $student->last_name }}, {{ $student->first_name }}</span>
                                <span class="mt-1 block text-sm text-slate-500">{{ $student->student_number }} · Grade {{ $student->grade_level ?? 'N/A' }}</span>
                            </span>
                        </a>
                    @empty
                        <p class="px-5 py-6 text-sm text-slate-500">No profiles yet.</p>
                    @endforelse
                </div>
            </div>

            @if (! auth()->user()->isAdmin())
            <div class="sh-panel overflow-hidden rounded-lg">
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
