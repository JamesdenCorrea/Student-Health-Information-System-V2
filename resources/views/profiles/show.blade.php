<x-layouts.app title="Profile Details">
    <section class="bg-gradient-to-r from-teal-950 via-teal-900 to-emerald-700 text-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div class="flex items-center gap-4">
                    <div class="grid size-20 shrink-0 place-items-center overflow-hidden rounded-lg border border-white/20 bg-white/10 text-xl font-semibold">
                        @if ($student->photo_path)
                            <img src="{{ asset('storage/'.$student->photo_path) }}" alt="{{ $student->first_name }} {{ $student->last_name }}" class="h-full w-full object-cover">
                        @else
                            {{ str($student->first_name)->substr(0, 1) }}{{ str($student->last_name)->substr(0, 1) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-teal-50/80">{{ $student->student_number }}</p>
                        <h1 class="mt-1 text-3xl font-semibold">{{ $student->first_name }} {{ $student->last_name }}</h1>
                        <p class="mt-2 text-teal-50/80">Grade {{ $student->grade_level ?? 'N/A' }} {{ $student->section }}</p>
                    </div>
                </div>
                @if (! auth()->user()->isParent())
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('profiles.edit', $student) }}" class="rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-teal-950 shadow-sm hover:bg-teal-50">Edit</a>
                        @if (auth()->user()->isClinicStaff())
                            <a href="{{ route('clinic-visits.create', $student) }}" class="rounded-md border border-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">Clinic visit</a>
                            <a href="{{ route('bmi-records.create', $student) }}" class="rounded-md border border-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">BMI</a>
                            <a href="{{ route('dental-records.edit', $student) }}" class="rounded-md border border-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">Dental</a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('profiles.destroy', $student) }}" onsubmit="return confirm('Delete this profile?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-md border border-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">Delete</button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
        <div class="space-y-6">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-slate-950">Basic student information</h2>
                <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        'Birth date' => $student->birth_date?->format('M d, Y') ?? 'N/A',
                        'Sex' => $student->sex ?? 'N/A',
                        'Status' => ucfirst($student->status),
                        'Guardian' => $student->guardian_name ?? 'N/A',
                        'Guardian contact' => $student->guardian_contact ?? 'N/A',
                        'Emergency contact' => $student->emergency_contact_name ?? 'N/A',
                        'Emergency phone' => $student->emergency_contact_phone ?? 'N/A',
                    ] as $label => $value)
                        <div>
                            <dt class="text-sm font-medium text-slate-500">{{ $label }}</dt>
                            <dd class="mt-1 text-sm text-slate-950">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            @if (auth()->user()->canViewHealthRecords($student))
                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-slate-950">Health profile</h2>
                    <dl class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach ([
                            'Blood type' => $student->healthProfile?->blood_type ?? 'N/A',
                            'Allergies' => implode(', ', $student->healthProfile?->allergies ?? []) ?: 'None listed',
                            'Chronic conditions' => implode(', ', $student->healthProfile?->chronic_conditions ?? []) ?: 'None listed',
                            'Medications' => implode(', ', $student->healthProfile?->medications ?? []) ?: 'None listed',
                            'Immunizations' => implode(', ', $student->healthProfile?->immunizations ?? []) ?: 'None listed',
                            'Physician' => $student->healthProfile?->physician_name ?? 'N/A',
                            'Physician contact' => $student->healthProfile?->physician_contact ?? 'N/A',
                        ] as $label => $value)
                            <div>
                                <dt class="text-sm font-medium text-slate-500">{{ $label }}</dt>
                                <dd class="mt-1 text-sm text-slate-950">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                    @if ($student->healthProfile?->notes)
                        <div class="mt-5 rounded-lg bg-slate-50 p-4 text-sm leading-6 text-slate-700">{{ $student->healthProfile->notes }}</div>
                    @endif
                </div>

                @if (auth()->user()->isParent())
                    @php
                        $latestBmi = $student->bmiRecords->first();
                        $latestVisit = $student->clinicVisits->first();
                        $recommendations = collect();
                        if ($latestBmi && $latestBmi->category !== 'Normal') {
                            $recommendations->push('Schedule a nutrition and activity follow-up because the latest BMI is '.$latestBmi->category.'.');
                        }
                        if (($student->healthProfile?->allergies ?? []) !== []) {
                            $recommendations->push('Keep allergy information updated and inform advisers about known allergy triggers.');
                        }
                        if ($latestVisit && $latestVisit->disposition === 'sent_home') {
                            $recommendations->push('Monitor symptoms at home and follow up with the clinic before returning to regular activities.');
                        }
                        if ($recommendations->isEmpty()) {
                            $recommendations->push('Maintain yearly BMI checks, dental reviews, updated immunizations, and regular hydration/sleep routines.');
                        }
                    @endphp
                    <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                        <h2 class="font-semibold text-emerald-950">Recommendations</h2>
                        <ul class="mt-4 space-y-2 text-sm leading-6 text-emerald-900">
                            @foreach ($recommendations as $recommendation)
                                <li>{{ $recommendation }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-slate-950">Yearly BMI</h2>
                    <div class="mt-4 divide-y divide-slate-100">
                        @forelse ($student->bmiRecords as $record)
                            <div class="grid gap-2 py-3 text-sm sm:grid-cols-4">
                                <span>{{ $record->school_year }}</span>
                                <span>{{ $record->height_cm }} cm</span>
                                <span>{{ $record->weight_kg }} kg</span>
                                <span class="font-medium">{{ $record->bmi }} · {{ $record->category }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No BMI records yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="font-semibold text-slate-950">Dental records</h2>
                    <div class="mt-4 grid grid-cols-4 gap-2 sm:grid-cols-8">
                        @foreach ($student->dentalRecords->groupBy('tooth_code')->map->first() as $tooth => $record)
                            <div class="rounded-md border border-slate-200 p-2 text-center text-xs">
                                <p class="font-semibold">{{ $tooth }}</p>
                                <p class="mt-1 text-slate-600">{{ $record->condition }}</p>
                            </div>
                        @endforeach
                    </div>
                    @if ($student->dentalRecords->isEmpty())
                        <p class="mt-4 text-sm text-slate-500">No dental records yet.</p>
                    @endif
                </div>
            @else
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-6 text-sm leading-6 text-amber-900">
                    Health records, clinic visits, BMI, and dental information are restricted to clinic staff and assigned parents.
                </div>
            @endif
        </div>

        @if (auth()->user()->canViewHealthRecords($student))
        <aside class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="font-semibold text-slate-950">Clinic visits</h2>
            <div class="mt-5 space-y-4">
                @forelse ($student->clinicVisits as $visit)
                    <div class="rounded-lg border border-slate-100 p-4">
                        <p class="font-medium text-slate-950">{{ $visit->complaint }}</p>
                        <p class="mt-1 text-sm text-slate-500">{{ $visit->visited_at?->format('M d, Y h:i A') }}</p>
                        @if ($visit->disposition)
                            <span class="mt-3 inline-flex rounded-full bg-teal-50 px-3 py-1 text-xs font-semibold text-teal-800">{{ $visit->disposition }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No clinic visits recorded.</p>
                @endforelse
            </div>
        </aside>
        @endif
    </section>
</x-layouts.app>
