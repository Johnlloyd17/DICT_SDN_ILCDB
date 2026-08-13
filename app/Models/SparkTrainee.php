<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparkTrainee extends Model
{
    use HasFactory;

    protected $table = 'spark_trainees';

    protected $fillable = [
        'trainee_code',
        'full_name',
        'specialty',
        'course',
        'municipality',
        'employment_status',
        'monthly_earnings',
    ];

    protected function casts(): array
    {
        return [
            'monthly_earnings' => 'decimal:2',
        ];
    }
}
