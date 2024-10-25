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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('code', 50)->nullable();
            $table->string('fullname')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('province_id', 20)->nullable();
            $table->string('district_id', 20)->nullable();
            $table->string('ward_id', 20)->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->json('promotion')->nullable();
            $table->json('cart')->nullable();
            $table->float('totalPrice')->default(0);
            $table->string('guest_cookie')->nullable();
            $table->string('method', 50)->nullable();
            $table->string('confirm', 50)->nullable();
            $table->string('payment', 50)->nullable();
            $table->string('delivery', 50)->nullable();
            $table->float('shipping')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
