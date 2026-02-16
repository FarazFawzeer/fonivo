<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesInvoice extends Model
{
    protected $fillable = [
        'customer_id','invoice_no','sale_date',
        'total_amount','paid_amount','balance_amount',
        'status','note','created_by'
    ];

    protected $casts = [
        'sale_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'related_id')
            ->where('related_type', 'sales_invoice');
    }
}
