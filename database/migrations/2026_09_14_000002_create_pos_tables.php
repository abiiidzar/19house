<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 40)->unique();
            $table->foreignId('cashier_id')->constrained('users')->restrictOnDelete();
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('total');
            $table->string('payment_method', 30);
            $table->string('payment_status', 30)->default('CONFIRMED');
            $table->string('payment_reference', 100)->nullable();
            $table->timestamp('payment_confirmed_at');
            $table->unsignedBigInteger('amount_received')->nullable();
            $table->unsignedBigInteger('change')->nullable();
            $table->string('status', 20)->default('COMPLETED');
            $table->timestamps();
            $table->unique(['payment_method', 'payment_reference']);
        });

        Schema::create('pos_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_sku_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quantity');
            $table->json('sku_snapshot');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_transaction_items');
        Schema::dropIfExists('pos_transactions');
    }
};
