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
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropForeign(['variant_uuid']);
            $table->foreign('variant_uuid')->references('uuid')->on('product_variants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropForeign(['foreign_key_column']);
            $table->foreign('variant_uuid')->references('uuid')->on('product_variants')->onDelete('cascade');
        });
    }
};
