<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                ->nullable()
                ->after('id')
                ->constrained()
                ->nullOnDelete();

            $table->string('phone', 30)
                ->nullable()
                ->after('email');

            $table->string('profile_photo_path')
                ->nullable()
                ->after('phone');

            $table->string('status', 20)
                ->default('ACTIVE')
                ->after('profile_photo_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);

            $table->dropColumn([
                'role_id',
                'phone',
                'profile_photo_path',
                'status',
            ]);
        });
    }
};
