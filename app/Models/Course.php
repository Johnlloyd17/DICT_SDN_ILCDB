<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'course_code',
        'title',
        'specialty_track',
        'format_type',
        'duration_hours',
        'credentials',
        'live_runs_completed',
        'live_runs_total',
        'reference_folders',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'array',
            'duration_hours' => 'integer',
            'live_runs_completed' => 'integer',
            'live_runs_total' => 'integer',
        ];
    }

    public function trainingBatches(): BelongsToMany
    {
        return $this->belongsToMany(TrainingBatch::class);
    }

    public function trainers(): BelongsToMany
    {
        return $this->belongsToMany(Trainer::class);
    }
}
