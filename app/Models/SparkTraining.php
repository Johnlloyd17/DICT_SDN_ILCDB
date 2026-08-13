<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparkTraining extends Model
{
    use HasFactory;

    protected $table = 'spark_trainings';

    protected $fillable = [
        'track_id',
        'specialization',
        'master_trainer',
        'enrolled_count',
        'budget_allocated',
        'industry_partner',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'enrolled_count' => 'integer',
            'budget_allocated' => 'decimal:2',
        ];
    }
}
