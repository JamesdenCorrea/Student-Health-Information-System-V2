@php
    $healthProfile = $student->healthProfile;
    $fieldClass = 'mt-2 w-full rounded-md border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-teal-800 focus:ring-2 focus:ring-teal-800/15';
    $labelClass = 'block text-sm font-medium text-slate-700';
    $arrayValue = fn ($field) => old("health_profile.$field", implode(', ', $healthProfile?->{$field} ?? []));
@endphp

@csrf
@if ($student->exists)
    @method('PUT')
@endif

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label class="{{ $labelClass }}" for="student_number">Student number</label>
        <input class="{{ $fieldClass }}" id="student_number" name="student_number" value="{{ old('student_number', $student->student_number) }}" required>
        @error('student_number') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="status">Status</label>
        <select class="{{ $fieldClass }}" id="status" name="status" required>
            @foreach (['active', 'inactive', 'transferred', 'graduated'] as $status)
                <option value="{{ $status }}" @selected(old('status', $student->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @error('status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="first_name">First name</label>
        <input class="{{ $fieldClass }}" id="first_name" name="first_name" value="{{ old('first_name', $student->first_name) }}" required>
        @error('first_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="middle_name">Middle name</label>
        <input class="{{ $fieldClass }}" id="middle_name" name="middle_name" value="{{ old('middle_name', $student->middle_name) }}">
        @error('middle_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="last_name">Last name</label>
        <input class="{{ $fieldClass }}" id="last_name" name="last_name" value="{{ old('last_name', $student->last_name) }}" required>
        @error('last_name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="birth_date">Birth date</label>
        <input class="{{ $fieldClass }}" id="birth_date" name="birth_date" type="date" value="{{ old('birth_date', $student->birth_date?->format('Y-m-d')) }}">
        @error('birth_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="sex">Sex</label>
        <input class="{{ $fieldClass }}" id="sex" name="sex" value="{{ old('sex', $student->sex) }}">
        @error('sex') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="grade_level">Grade level</label>
        <input class="{{ $fieldClass }}" id="grade_level" name="grade_level" value="{{ old('grade_level', $student->grade_level) }}">
        @error('grade_level') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="section">Section</label>
        <input class="{{ $fieldClass }}" id="section" name="section" value="{{ old('section', $student->section) }}">
        @error('section') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="{{ $labelClass }}" for="blood_type">Blood type</label>
        <input class="{{ $fieldClass }}" id="blood_type" name="health_profile[blood_type]" value="{{ old('health_profile.blood_type', $healthProfile?->blood_type) }}">
        @error('health_profile.blood_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-8 grid gap-5 md:grid-cols-2">
    <div>
        <label class="{{ $labelClass }}" for="guardian_name">Guardian name</label>
        <input class="{{ $fieldClass }}" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="guardian_contact">Guardian contact</label>
        <input class="{{ $fieldClass }}" id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact', $student->guardian_contact) }}">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="emergency_contact_name">Emergency contact name</label>
        <input class="{{ $fieldClass }}" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="emergency_contact_phone">Emergency contact phone</label>
        <input class="{{ $fieldClass }}" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}">
    </div>
</div>

<div class="mt-8 grid gap-5 md:grid-cols-2">
    <div>
        <label class="{{ $labelClass }}" for="allergies">Allergies</label>
        <input class="{{ $fieldClass }}" id="allergies" name="health_profile[allergies]" value="{{ $arrayValue('allergies') }}" placeholder="Comma separated">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="chronic_conditions">Chronic conditions</label>
        <input class="{{ $fieldClass }}" id="chronic_conditions" name="health_profile[chronic_conditions]" value="{{ $arrayValue('chronic_conditions') }}" placeholder="Comma separated">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="medications">Medications</label>
        <input class="{{ $fieldClass }}" id="medications" name="health_profile[medications]" value="{{ $arrayValue('medications') }}" placeholder="Comma separated">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="immunizations">Immunizations</label>
        <input class="{{ $fieldClass }}" id="immunizations" name="health_profile[immunizations]" value="{{ $arrayValue('immunizations') }}" placeholder="Comma separated">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="physician_name">Physician name</label>
        <input class="{{ $fieldClass }}" id="physician_name" name="health_profile[physician_name]" value="{{ old('health_profile.physician_name', $healthProfile?->physician_name) }}">
    </div>
    <div>
        <label class="{{ $labelClass }}" for="physician_contact">Physician contact</label>
        <input class="{{ $fieldClass }}" id="physician_contact" name="health_profile[physician_contact]" value="{{ old('health_profile.physician_contact', $healthProfile?->physician_contact) }}">
    </div>
</div>

<div class="mt-8">
    <label class="{{ $labelClass }}" for="notes">Health notes</label>
    <textarea class="{{ $fieldClass }} min-h-28" id="notes" name="health_profile[notes]">{{ old('health_profile.notes', $healthProfile?->notes) }}</textarea>
</div>

<div class="mt-8 flex justify-end gap-3">
    <a href="{{ route('profiles.index') }}" class="rounded-md border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-teal-800 hover:text-teal-950">Cancel</a>
    <button class="rounded-md bg-gradient-to-r from-teal-950 via-teal-800 to-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-teal-900 hover:to-emerald-500">{{ $submitLabel }}</button>
</div>
