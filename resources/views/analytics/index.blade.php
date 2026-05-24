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
            @else
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm md:col-span-2">
                    <p class="text-sm text-slate-500">Health privacy</p>
                    <p class="mt-3 text-sm leading-6 text-slate-700">Admin analytics exclude health records, clinic visits, dental records, and BMI data.</p>
                </div>
            @endif
        </div>

        <div class="mt-6 grid gap-6 lg:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-950">Students by status</h2>
                @php $statusMax = max(1, (int) $statusCounts->max()); @endphp
                <div class="mt-5 space-y-4">
                    @forelse ($statusCounts as $status => $total)
                        <div>
                            <div class="mb-1 flex justify-between text-sm">
                                <span class="font-medium text-slate-700">{{ ucfirst($status) }}</span>
                                <span class="text-slate-500">{{ $total }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-teal-800" style="width: {{ max(6, ($total / $statusMax) * 100) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No status data yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-950">Students by grade</h2>
                @php $gradeMax = max(1, (int) $gradeCounts->max()); @endphp
                <div class="mt-5 flex h-56 items-end gap-3 border-b border-slate-200 pb-2">
                    @forelse ($gradeCounts as $grade => $total)
                        <div class="flex min-w-10 flex-1 flex-col items-center gap-2">
                            <div class="w-full rounded-t-md bg-emerald-600" style="height: {{ max(8, ($total / $gradeMax) * 180) }}px"></div>
                            <span class="text-xs text-slate-500">{{ $grade ?: 'N/A' }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No grade data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        @if (! auth()->user()->isAdmin())
            <div class="mt-6 grid gap-6 lg:grid-cols-2">
                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-950">Clinic visit trend</h2>
                    @php $visitMax = max(1, (int) $visitTrend->max()); @endphp
                    <div class="mt-5 flex h-56 items-end gap-3 border-b border-slate-200 pb-2">
                        @forelse ($visitTrend as $date => $total)
                            <div class="flex min-w-10 flex-1 flex-col items-center gap-2">
                                <div class="w-full rounded-t-md bg-teal-800" style="height: {{ max(8, ($total / $visitMax) * 180) }}px"></div>
                                <span class="text-xs text-slate-500">{{ \Illuminate\Support\Carbon::parse($date)->format('M d') }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No clinic visit data yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="font-semibold text-slate-950">BMI categories</h2>
                    @php $bmiMax = max(1, (int) $bmiCategories->max()); @endphp
                    <div class="mt-5 space-y-4">
                        @forelse ($bmiCategories as $category => $total)
                            <div>
                                <div class="mb-1 flex justify-between text-sm">
                                    <span class="font-medium text-slate-700">{{ $category }}</span>
                                    <span class="text-slate-500">{{ $total }}</span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-emerald-600" style="width: {{ max(6, ($total / $bmiMax) * 100) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No BMI data yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

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
