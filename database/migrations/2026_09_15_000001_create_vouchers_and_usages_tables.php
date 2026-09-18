<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('type', 20);
            $table->unsignedBigInteger('value');
            $table->unsignedBigInteger('min_purchase')->default(0);
            $table->unsignedBigInteger('max_discount')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('per_user_limit')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->restrictOnDelete();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('discount_amount');
            $table->string('status', 20)->default('RESERVED');
            $table->timestamp('redeemed_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();

            $table->unique('order_id');
            $table->index(['voucher_id', 'status']);
            $table->index(['voucher_id', 'user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('vouchers');
    }
};
