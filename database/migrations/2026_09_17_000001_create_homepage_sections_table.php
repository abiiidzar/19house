<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('slot')->nullable()->unique();
            $table->string('type', 20);
            $table->string('eyebrow', 100)->nullable();
            $table->string('title', 180);
            $table->string('subtitle', 500)->nullable();
            $table->string('button_label', 60)->nullable();
            $table->string('button_path', 255)->nullable();
            $table->string('image_path')->nullable();
            $table->string('mobile_image_path')->nullable();
            $table->string('text_position', 20)->default('left');
            $table->string('text_color', 20)->default('light');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['type', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
