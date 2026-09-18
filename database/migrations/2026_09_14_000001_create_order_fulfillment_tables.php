<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_status_histories', function (Blueprint $table) {
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('courier', 100);
            $table->string('service', 100);
            $table->string('tracking_number', 100);
            $table->unsignedBigInteger('shipping_cost')->default(0);
            $table->string('status', 30)->default('SHIPPED');
            $table->timestamp('shipped_at');
            $table->timestamp('delivered_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->text('reason');
            $table->string('status', 30)->default('REQUESTED');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_note')->nullable();
            $table->timestamp('requested_at');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('cancellation_requests');
        Schema::dropIfExists('shipments');
        Schema::table('order_status_histories', fn (Blueprint $table) => $table->dropConstrainedForeignId('actor_id'));
    }
};
