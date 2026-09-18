<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_sku_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('price');
            $table->unsignedInteger('quantity');
            $table->json('sku_snapshot'); // Menyimpan data produk saat dibeli
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); }
};
