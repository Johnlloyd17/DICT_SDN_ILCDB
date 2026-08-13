<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Trainer extends Model
{
    use HasFactory;

    protected $table = 'trainers';

    protected $fillable = [
        'full_name',
        'designation',
        'specialty',
        'agency',
        'contact',
        'phone',
        'courses',
        'rating',
        'accreditation',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'courses' => 'integer',
            'rating' => 'float',
        ];
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }
}
