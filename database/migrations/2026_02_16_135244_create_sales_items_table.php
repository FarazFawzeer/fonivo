<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained()->cascadeOnDelete();

            $table->foreignId('phone_unit_id')->nullable()->constrained('phone_units')->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();

            $table->integer('qty')->default(1); // accessories qty. for phone keep 1.
            $table->decimal('unit_sell_price', 12, 2)->default(0);

            // profit snapshot (important)
            $table->decimal('unit_cost_price_snapshot', 12, 2)->default(0);

            $table->decimal('line_total', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['sales_invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_items');
    }
};
