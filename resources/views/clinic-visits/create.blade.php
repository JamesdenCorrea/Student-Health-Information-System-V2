<x-layouts.app title="New Clinic Visit">
    <section class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <p class="text-sm font-medium text-teal-800">{{ $student->student_number }}</p>
            <h1 class="mt-1 text-3xl font-semibold text-slate-950">Record clinic visit</h1>
        </div>

        <form method="POST" action="{{ route('clinic-visits.store', $student) }}" class="grid gap-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:grid-cols-2">
            @csrf
            <input name="visited_at" type="datetime-local" value="{{ old('visited_at', now()->format('Y-m-d\TH:i')) }}" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm" required>
            <input name="nurse_name" value="{{ old('nurse_name', auth()->user()->name) }}" placeholder="Nurse name" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            <textarea name="complaint" placeholder="Complaint" class="min-h-24 rounded-md border border-slate-300 px-3 py-2.5 text-sm md:col-span-2" required>{{ old('complaint') }}</textarea>
            <textarea name="assessment" placeholder="Assessment" class="min-h-24 rounded-md border border-slate-300 px-3 py-2.5 text-sm">{{ old('assessment') }}</textarea>
            <textarea name="treatment" placeholder="Treatment" class="min-h-24 rounded-md border border-slate-300 px-3 py-2.5 text-sm">{{ old('treatment') }}</textarea>
            <input name="temperature" value="{{ old('temperature') }}" placeholder="Temperature" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            <input name="blood_pressure" value="{{ old('blood_pressure') }}" placeholder="Blood pressure" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            <input name="pulse_rate" value="{{ old('pulse_rate') }}" placeholder="Pulse rate" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            <select name="disposition" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
                <option value="">Disposition</option>
                <option value="returned_to_class">Returned to class</option>
                <option value="sent_home">Sent home due to illness</option>
                <option value="referred">Referred</option>
            </select>
            <input name="follow_up_at" type="datetime-local" value="{{ old('follow_up_at') }}" class="rounded-md border border-slate-300 px-3 py-2.5 text-sm">
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="notify_parent" value="1" class="rounded border-slate-300">
                Queue SMS notification when sent home
            </label>
            <div class="flex justify-end gap-3 md:col-span-2">
                <a href="{{ route('profiles.show', $student) }}" class="rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Cancel</a>
                <button class="rounded-md bg-teal-950 px-4 py-2.5 text-sm font-semibold text-white">Save visit</button>
            </div>
        </form>
    </section>
</x-layouts.app>
