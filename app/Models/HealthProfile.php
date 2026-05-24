<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'student_id',
    'blood_type',
    'allergies',
    'chronic_conditions',
    'medications',
    'immunizations',
    'physician_name',
    'physician_contact',
    'notes',
])]
class HealthProfile extends Model
{
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    protected function casts(): array
    {
        return [
            'allergies' => 'array',
            'chronic_conditions' => 'array',
            'medications' => 'array',
            'immunizations' => 'array',
        ];
    }
}
