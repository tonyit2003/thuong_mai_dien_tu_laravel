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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->string('variant_uuid', 36)->nullable()->collation('utf8mb4_general_ci');
            $table->foreign('variant_uuid')->references('uuid')->on('product_variants')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->float('price')->default(0);
            $table->float('priceOriginal')->default(0);
            $table->json('promotion')->nullable();
            $table->json('option')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            Schema::dropIfExists('order_product');
        });
    }
};
