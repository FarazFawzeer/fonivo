<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLedger extends Model
{
    protected $fillable = [
        'product_id','qty_in','qty_out',
        'reference_type','reference_id',
        'entry_date','note','created_by'
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
