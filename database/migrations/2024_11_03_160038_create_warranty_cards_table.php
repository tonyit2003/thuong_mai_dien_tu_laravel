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
        Schema::create('warranty_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('variant_uuid', 36)->nullable();

            $table->timestamp('warranty_start_date')->nullable(); // Ngày bắt đầu bảo hành
            $table->timestamp('warranty_end_date')->nullable(); // Ngày kết thúc bảo hành
            $table->enum('status', ['active', 'expired', 'pending', 'completed'])->default('active'); // Trạng thái bảo hành
            $table->text('notes')->nullable(); // Ghi chú thêm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranty_cards');
    }
};
