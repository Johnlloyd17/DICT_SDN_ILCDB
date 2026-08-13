<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DtcCenterInventory extends Model
{
    use HasFactory;

    protected $table = 'dtc_center_inventories';

    protected $fillable = [
        'congressional_district',
        'province',
        'municipality_city',
        'barangay',
        'center_name',
        'longitude',
        'latitude',
        'verified',
        'moa_date_of_signing',
        'date_of_launching',
        'date_of_platform_registration',
        'tcms_status',
        'tcms_key',
        'tcms_identifier',
        'tcms_verification_status',
        'odk_status',
        'connectivity_status',
        'type_of_center_host',
        'operational_status',
    ];

    protected function casts(): array
    {
        return [
            'verified' => 'boolean',
            'longitude' => 'decimal:7',
            'latitude' => 'decimal:7',
            'moa_date_of_signing' => 'date',
            'date_of_launching' => 'date',
            'date_of_platform_registration' => 'date',
        ];
    }
}
