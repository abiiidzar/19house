<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_sku_id')
                ->unique() // Satu SKU hanya punya 1 record stock
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('on_hand')->default(0);
            $table->unsignedInteger('reserved')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
