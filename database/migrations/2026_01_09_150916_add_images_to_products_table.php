<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->string('name');               // iPhone 13 128GB / USB Cable
            $table->string('brand')->nullable();  // Apple / Anker
            $table->string('model')->nullable();  // iPhone 13 / Cable Type-C

            // Mostly for accessories
            $table->string('sku')->nullable()->unique();

            // Default prices (optional)
            $table->decimal('default_cost_price', 12, 2)->nullable();
            $table->decimal('default_sell_price', 12, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
