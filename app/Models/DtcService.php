<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DtcService extends Model
{
    use HasFactory;

    protected $table = 'dtc_services';

    protected $fillable = [
        'dtc_hub_id',
        'service_name',
        'category',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function dtcHub(): BelongsTo
    {
        return $this->belongsTo(DtcHub::class);
    }

    public function visitServices(): HasMany
    {
        return $this->hasMany(VisitService::class, 'service_id');
    }

    public function visits(): BelongsToMany
    {
        return $this->belongsToMany(Visit::class, 'visit_services', 'service_id', 'visit_id')
            ->withPivot('status', 'remarks');
    }
}