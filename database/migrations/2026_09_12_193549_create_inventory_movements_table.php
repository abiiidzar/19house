<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_sku_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type', 30); // STOCK_IN, ADJUSTMENT_IN, ADJUSTMENT_OUT, RESERVATION, etc
            $table->integer('quantity'); // Bisa positif (masuk) atau negatif (keluar)

            $table->unsignedInteger('before');
            $table->unsignedInteger('after');

            $table->string('reference')->nullable(); // Contoh: PO-001, Order-123
            $table->string('reason')->nullable(); // Wajib ada jika type ADJUSTMENT

            $table->foreignId('actor_id') // User yang melakukan aksi
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['product_sku_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_movements');
    }
};
