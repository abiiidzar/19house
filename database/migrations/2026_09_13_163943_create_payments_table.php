<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 50); // e.g., MOCK, MIDTRANS, XENDIT
            $table->string('provider_payment_id')->unique()->nullable(); // ID dari gateway
            $table->string('payment_reference')->unique(); // ID internal kita
            $table->unsignedBigInteger('amount');
            $table->string('status', 20)->default('PENDING'); // PENDING, PAID, EXPIRED, FAILED
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
