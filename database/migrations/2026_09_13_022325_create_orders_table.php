<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status', 30)->default('PENDING_PAYMENT');
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->unsignedBigInteger('discount')->default(0);
            $table->unsignedBigInteger('total');
            $table->string('shipping_method', 50);
            $table->json('address_snapshot');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
