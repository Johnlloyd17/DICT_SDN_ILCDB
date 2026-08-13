<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TmdPenetration extends Model
{
    protected $table = 'tmd_penetration';

    protected $fillable = [
        'municipality',
        'male',
        'female',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'male' => 'integer',
            'female' => 'integer',
            'total' => 'integer',
        ];
    }
}
