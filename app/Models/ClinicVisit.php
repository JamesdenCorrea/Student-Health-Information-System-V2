<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'student_id',
    'visited_at',
    'complaint',
    'assessment',
    'treatment',
    'temperature',
    'blood_pressure',
    'pulse_rate',
    'nurse_name',
    'disposition',
    'follow_up_at',
])]
class ClinicVisit extends Model
{
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    protected function casts(): array
    {
        return [
            'visited_at' => 'datetime',
            'follow_up_at' => 'datetime',
            'temperature' => 'decimal:1',
        ];
    }
}
