<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('party_type'); // supplier | customer
            $table->unsignedBigInteger('party_id');

            $table->string('related_type'); // purchase_invoice | sales_invoice
            $table->unsignedBigInteger('related_id');

            $table->decimal('amount', 12, 2);
            $table->date('paid_at');

            $table->string('method')->nullable(); // cash/bank/card
            $table->string('reference_no')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['party_type', 'party_id']);
            $table->index(['related_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
