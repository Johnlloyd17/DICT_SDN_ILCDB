<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DtcVisitorLog extends Model
{
    use HasFactory;

    protected $table = 'dtc_visitor_logs';

    protected $fillable = [
        'log_code',
        'visitor_name',
        'gender',
        'age',
        'demographic_sector',
        'dtc_hub_id',
        'services_ailed',
        'session_duration',
        'visit_date',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'services_ailed' => 'array',
            'visit_date' => 'datetime',
        ];
    }

    public function dtcHub(): BelongsTo
    {
        return $this->belongsTo(DtcHub::class);
    }
}
