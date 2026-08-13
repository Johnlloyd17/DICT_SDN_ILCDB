<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingBatch extends Model
{
    use HasFactory;

    protected $table = 'training_batches';

    protected $fillable = [
        'batch_code',
        'course_title',
        'venue',
        'target_count',
        'enrolled_count',
        'trainer_name',
        'start_date',
        'end_date',
        'program',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'target_count' => 'integer',
            'enrolled_count' => 'integer',
        ];
    }

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}
