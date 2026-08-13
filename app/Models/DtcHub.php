<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DtcHub extends Model
{
    use HasFactory;

    protected $table = 'dtc_hubs';

    protected $fillable = [
        'name',
        'municipality',
        'latitude',
        'longitude',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function visitorLogs(): HasMany
    {
        return $this->hasMany(DtcVisitorLog::class);
    }
}
