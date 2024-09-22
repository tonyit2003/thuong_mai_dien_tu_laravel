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
        Schema::table('promotions', function (Blueprint $table) {
            $table->integer('discountValue')->default(0)->nullable();
            $table->string('discountType', 10)->nullable();
            $table->integer('maxDiscountValue')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('discountValue');
            $table->dropColumn('discountType');
            $table->dropColumn('maxDiscountValue');
        });
    }
};
