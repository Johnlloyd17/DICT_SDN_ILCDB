<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Participant extends Model
{
    use HasFactory;

    protected $table = 'participants';

    protected $fillable = [
        'participant_code',
        'full_name',
        'training_batch_id',
        'agency_sector',
        'municipality',
        'completion_status',
        'completion_date',
        'certificate_file',
    ];

    protected function casts(): array
    {
        return [
            'completion_date' => 'date',
        ];
    }

    public function trainingBatch(): BelongsTo
    {
        return $this->belongsTo(TrainingBatch::class);
    }
}
