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
        Schema::table('carts', function (Blueprint $table) {
            Schema::dropIfExists('carts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(column: 'customer_id');
            $table->string('variant_uuid', 36)->nullable()->collation('utf8mb4_general_ci');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('variant_uuid')->references('uuid')->on('product_variants')->onDelete('cascade');

            $table->timestamps();
        });
    }
};
