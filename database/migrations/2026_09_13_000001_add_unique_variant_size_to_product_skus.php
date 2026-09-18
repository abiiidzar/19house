<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->unique(['product_variant_id', 'size_id'], 'product_skus_variant_size_unique');
        });
    }

    public function down(): void
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->dropUnique('product_skus_variant_size_unique');
        });
    }
};
