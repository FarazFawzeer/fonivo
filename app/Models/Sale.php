<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = [
        'product_id',
        'sale_date',
        'sale_price',
        'payment_method',
        'credit_due_date',
        'credit_amount',
        'customer_name',
        'customer_contact',
    ];

    protected $casts = [
        'sale_date' => 'date',
        'credit_due_date' => 'date',
        'sale_price' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    // Relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
