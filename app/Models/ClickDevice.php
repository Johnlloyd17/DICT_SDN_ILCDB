<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClickDevice extends Model
{
    use HasFactory;

    protected $table = 'click_devices';

    protected $fillable = [
        'batch_id',
        'donation_date',
        'device_type',
        'quantity',
        'beneficiary',
        'municipality',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'donation_date' => 'date',
            'quantity' => 'integer',
        ];
    }
}
