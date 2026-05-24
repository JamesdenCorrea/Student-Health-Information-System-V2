<x-layouts.app title="BMI Check">
    <section class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">{{ $student->student_number }}</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Yearly BMI check</h1>
        </div>

        <form method="POST" action="{{ route('bmi-records.store', $student) }}" class="grid gap-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
            @csrf
            <input name="school_year" value="{{ old('school_year', now()->year) }}" placeholder="School year" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
            <input name="checked_at" type="date" value="{{ old('checked_at', now()->format('Y-m-d')) }}" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
            <input name="height_cm" value="{{ old('height_cm') }}" placeholder="Height in cm" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
            <input name="weight_kg" value="{{ old('weight_kg') }}" placeholder="Weight in kg" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
            <div class="flex justify-end gap-3 md:col-span-2">
                <a href="{{ route('profiles.show', $student) }}" class="rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Cancel</a>
                <button class="rounded-md bg-teal-950 px-4 py-2.5 text-sm font-semibold text-white">Save BMI</button>
            </div>
        </form>
    </section>
</x-layouts.app>
