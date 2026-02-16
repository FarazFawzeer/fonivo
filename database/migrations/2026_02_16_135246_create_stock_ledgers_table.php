<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->integer('qty_in')->default(0);
            $table->integer('qty_out')->default(0);

            $table->string('reference_type')->nullable(); // purchase_invoice | sales_invoice | adjustment
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->date('entry_date');
            $table->text('note')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['product_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
