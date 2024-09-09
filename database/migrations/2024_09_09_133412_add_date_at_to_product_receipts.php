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
        Schema::table('product_receipts', function (Blueprint $table) {
            $table->timestamp('date_created')->nullable()->change();
            $table->timestamp('date_of_booking')->nullable();
            $table->timestamp('date_approved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_receipts', function (Blueprint $table) {
            $table->dropColumn(['date_of_booking', 'date_approved']);
            $table->timestamp('date_created')->nullable(false)->change();
        });
    }
};
