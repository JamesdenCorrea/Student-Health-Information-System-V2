<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['student_id', 'recorded_by', 'tooth_code', 'condition', 'notes', 'recorded_at'])]
class DentalRecord extends Model
{
    public const CONDITIONS = ['healthy', 'decayed', 'filled', 'missing', 'removed', 'watch'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    protected function casts(): array
    {
        return [
            'recorded_at' => 'date',
        ];
    }
}
