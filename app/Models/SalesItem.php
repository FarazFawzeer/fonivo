<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    protected $fillable = [
        'sales_invoice_id','phone_unit_id','product_id',
        'qty','unit_sell_price','unit_cost_price_snapshot','line_total'
    ];

    protected $casts = [
        'unit_sell_price' => 'decimal:2',
        'unit_cost_price_snapshot' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function phoneUnit()
    {
        return $this->belongsTo(PhoneUnit::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
