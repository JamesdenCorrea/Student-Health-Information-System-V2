<x-layouts.app title="Dental Records">
    <section class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">{{ $student->student_number }}</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Dental tooth diagram</h1>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
            <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                <div class="grid grid-cols-8 gap-2">
                    @foreach ($teeth as $tooth)
                        @php
                            $record = $student->dentalRecords->where('tooth_code', $tooth)->sortByDesc('recorded_at')->first();
                            $condition = $record?->condition ?? 'healthy';
                            $tone = match ($condition) {
                                'decayed' => 'border-red-300 bg-red-50 text-red-800',
                                'missing', 'removed' => 'border-slate-400 bg-slate-200 text-slate-800',
                                'filled' => 'border-blue-300 bg-blue-50 text-blue-800',
                                'watch' => 'border-amber-300 bg-amber-50 text-amber-800',
                                default => 'border-emerald-200 bg-emerald-50 text-emerald-800',
                            };
                        @endphp
                        <button form="dental-form" name="tooth_code" value="{{ $tooth }}" class="aspect-square rounded-md border text-sm font-semibold {{ $tone }}" title="{{ $condition }}">{{ $tooth }}</button>
                    @endforeach
                </div>
            </div>

            <form id="dental-form" method="POST" action="{{ route('dental-records.store', $student) }}" class="h-fit rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <h2 class="font-semibold text-slate-950">Update selected tooth</h2>
                <p class="mt-2 text-sm text-slate-500">Click a tooth after choosing the condition. The new entry becomes the latest state.</p>
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
