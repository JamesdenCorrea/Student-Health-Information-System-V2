<x-layouts.app title="Dental Records">
    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">{{ $student->student_number }}</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Dental tooth diagram</h1>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                @php
                    $upper = array_slice($teeth, 0, 16);
                    $lower = array_slice($teeth, 16);
                    $toothClass = function ($condition): string {
                        return match ($condition) {
                            'decayed' => 'tooth-decayed',
                            'missing', 'removed' => 'tooth-missing',
                            'filled' => 'tooth-filled',
                            'watch' => 'tooth-watch',
                            default => 'tooth-healthy',
                        };
                    };
                @endphp

                <div class="rounded-full border border-slate-200 bg-slate-50 px-4 py-8">
                    <div class="mx-auto max-w-4xl">
                        <p class="mb-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Upper arch</p>
                        <div class="dental-arch dental-arch-upper">
                            @foreach ($upper as $tooth)
                                @php
                                    $record = $student->dentalRecords->where('tooth_code', $tooth)->sortByDesc('recorded_at')->first();
                                    $condition = $record?->condition ?? 'healthy';
                                @endphp
                                <button form="dental-form" name="tooth_code" value="{{ $tooth }}" class="tooth {{ $toothClass($condition) }}" title="{{ $tooth }} · {{ $condition }}">
                                    <span>{{ $tooth }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="my-6 h-px bg-slate-200"></div>

                        <p class="mb-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Lower arch</p>
                        <div class="dental-arch dental-arch-lower">
                            @foreach ($lower as $tooth)
                                @php
                                    $record = $student->dentalRecords->where('tooth_code', $tooth)->sortByDesc('recorded_at')->first();
                                    $condition = $record?->condition ?? 'healthy';
                                @endphp
                                <button form="dental-form" name="tooth_code" value="{{ $tooth }}" class="tooth tooth-lower {{ $toothClass($condition) }}" title="{{ $tooth }} · {{ $condition }}">
                                    <span>{{ $tooth }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-5 flex flex-wrap gap-3 text-xs text-slate-600">
                    @foreach (['healthy' => 'Healthy', 'watch' => 'Watch', 'filled' => 'Filled', 'decayed' => 'Decayed', 'missing' => 'Missing/removed'] as $class => $label)
                        <span class="inline-flex items-center gap-2"><span class="size-3 rounded-full tooth-{{ $class }}"></span>{{ $label }}</span>
                    @endforeach
                </div>
            </div>

            <form id="dental-form" method="POST" action="{{ route('dental-records.store', $student) }}" class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <h2 class="font-semibold text-slate-950">Update selected tooth</h2>
                <p class="mt-2 text-sm text-slate-500">Choose a condition, add notes, then click a tooth on the diagram.</p>
                <select name="condition" class="mt-5 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition }}">{{ ucfirst($condition) }}</option>
                    @endforeach
                </select>
                <input name="recorded_at" type="date" value="{{ now()->format('Y-m-d') }}" class="mt-4 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
                <textarea name="notes" placeholder="Notes" class="mt-4 min-h-24 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm"></textarea>
            </form>
        </div>
    </section>
</x-layouts.app>
