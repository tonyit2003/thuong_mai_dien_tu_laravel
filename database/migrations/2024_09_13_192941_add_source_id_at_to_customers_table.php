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
        Schema::table('customers', function (Blueprint $table) {
            // add dữ liệu vào bảng sources trước => lấy default từ 1 dòng bất kỳ
            $table->unsignedBigInteger('source_id')->default(3);
            $table->foreign('source_id')->references('id')->on('sources');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('source_id');
            $table->dropForeign(['source_id']);
        });
    }
};
