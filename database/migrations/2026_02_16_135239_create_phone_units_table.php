<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('phone_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('imei1')->unique();
            $table->string('imei2')->nullable()->unique();

            $table->string('condition')->nullable();       // A/B/C or Good
            $table->string('battery_health')->nullable();  // 85%
            $table->text('faults')->nullable();
            $table->text('included_items')->nullable();    // box, charger etc.
            $table->integer('warranty_days')->default(0);

            $table->decimal('purchase_cost', 12, 2)->nullable();
            $table->decimal('expected_sell_price', 12, 2)->nullable();

            $table->string('status')->default('available'); // available | sold | reserved | returned
            $table->timestamps();

            $table->index(['product_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_units');
    }
};
