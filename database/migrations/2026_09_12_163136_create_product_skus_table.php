<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('product_variant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('size_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('sku')->unique();

            $table->unsignedBigInteger('price')->nullable(); // Jika null, ambil dari base_price product

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_skus');
    }
};
