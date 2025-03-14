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
            $table->decimal('total', 16, 2)->default(0)->change();
            $table->decimal('actual_total', 16, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_receipts', function (Blueprint $table) {
            $table->decimal('total', 12, 2)->default(0)->change();
            $table->decimal('actual_total', 12, 2)->default(0)->change();
        });
    }
};
