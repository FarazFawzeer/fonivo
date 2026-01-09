<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Table name (optional if it follows Laravel convention)
    protected $table = 'products';

    // Fillable fields for mass assignment
    protected $fillable = [
        'product_type',
        'name',
        'owner_name',
        'owner_contact',
        'purchase_date',
        'cost_price',
        'selling_price',
        'stock_status',
        'images', // Add this
    ];

    // Cast images as array
    protected $casts = [
        'purchase_date' => 'date',
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'images' => 'array', // Important for multiple images
    ];
}
