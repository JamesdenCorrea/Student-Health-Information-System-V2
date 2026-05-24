<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'student_number',
    'first_name',
    'middle_name',
    'last_name',
    'birth_date',
    'sex',
    'grade_level',
    'section',
    'guardian_name',
    'guardian_contact',
    'emergency_contact_name',
    'emergency_contact_phone',
    'status',
])]
class Student extends Model
{
    public function healthProfile(): HasOne
    {
        return $this->hasOne(HealthProfile::class);
    }

    public function clinicVisits(): HasMany
    {
        return $this->hasMany(ClinicVisit::class);
    }

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }
}
