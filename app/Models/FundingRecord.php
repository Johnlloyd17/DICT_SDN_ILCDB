<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundingRecord extends Model
{
    use HasFactory;

    protected $table = 'funding_records';

    protected $fillable = [
        'voucher_ref',
        'project',
        'description',
        'expense_category',
        'allocated',
        'obligated',
        'disbursed',
        'transaction_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'allocated' => 'decimal:2',
            'obligated' => 'decimal:2',
            'disbursed' => 'decimal:2',
            'transaction_date' => 'date',
        ];
    }
}
