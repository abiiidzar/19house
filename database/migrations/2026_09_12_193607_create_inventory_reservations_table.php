<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_sku_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity');

            $table->string('status', 20)->default('PENDING'); // PENDING, CONSUMED, RELEASED, EXPIRED

            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_reservations');
    }
};
