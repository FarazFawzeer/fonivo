<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable = [
        'purchase_invoice_id','phone_unit_id','product_id',
        'qty','unit_cost_price','line_total'
    ];

    protected $casts = [
        'unit_cost_price' => 'decimal:2',
        'line_total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
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
