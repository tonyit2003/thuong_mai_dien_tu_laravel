<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('promotion_product_variant', function (Blueprint $table) {
            $table->renameColumn('product_variant_id', 'variant_uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_product_variant', function (Blueprint $table) {
            $table->renameColumn('variant_uuid', 'product_variant_id');
        });
    }
};
