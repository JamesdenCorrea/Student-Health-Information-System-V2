<x-layouts.app title="Analytics">
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">Descriptive analytics</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Current snapshot</h1>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Students</p>
                <p class="mt-3 text-3xl font-semibold">{{ $studentCount }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm text-slate-500">Active</p>
                <p class="mt-3 text-3xl font-semibold">{{ $activeCount }}</p>
            </div>
            @if (! auth()->user()->isAdmin())
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Clinic visits</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $clinicVisitCount }}</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-slate-500">Dental concerns</p>
                    <p class="mt-3 text-3xl font-semibold">{{ $dentalConcernCount }}</p>
                </div>
            @endif
        </div>

        @if (auth()->user()->isAdmin())
            <div class="mt-6 rounded-lg border border-amber-200 bg-amber-50 p-5 text-sm leading-6 text-amber-900">
                Admin analytics intentionally exclude health-related details. Clinic staff can view health analytics.
            </div>
        @else
            <div class="mt-6 rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-950">Latest BMI checks</h2>
                <div class="mt-4 divide-y divide-slate-100">
                    @forelse ($latestBmi as $record)
                        <div class="grid gap-2 py-3 text-sm md:grid-cols-4">
                            <span>{{ $record->student->last_name }}, {{ $record->student->first_name }}</span>
                            <span>{{ $record->school_year }}</span>
                            <span>{{ $record->bmi }}</span>
                            <span>{{ $record->category }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No BMI data yet.</p>
                    @endforelse
                </div>
            </div>
        @endif
    </section>
</x-layouts.app>
