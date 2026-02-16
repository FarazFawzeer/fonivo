<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'party_type','party_id',
        'related_type','related_id',
        'amount','paid_at','method','reference_no','note','created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'date',
    ];
}
