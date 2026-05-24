<x-layouts.app title="Profile Details">
    <section class="bg-gradient-to-r from-teal-950 via-teal-900 to-emerald-700 text-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-sm font-medium text-teal-50/80">{{ $student->student_number }}</p>
                    <h1 class="mt-1 text-3xl font-semibold">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="mt-2 text-teal-50/80">Grade {{ $student->grade_level ?? 'N/A' }} {{ $student->section }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('profiles.edit', $student) }}" class="rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-teal-950 shadow-sm hover:bg-teal-50">Edit</a>
                    <form method="POST" action="{{ route('profiles.destroy', $student) }}" onsubmit="return confirm('Delete this profile?')">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-md border border-white/30 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/10">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto grid max-w-7xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8">
        <div class="space-y-6">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-semibold text-slate-950">Student information</h2>
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
        </div>

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
    </section>
</x-layouts.app>
