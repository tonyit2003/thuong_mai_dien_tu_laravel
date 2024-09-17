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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code', 20)->nullable();
            $table->text('description')->nullable();
            $table->string('method')->nullable();
            $table->json('discountInformation')->nullable();
            $table->string('neverEndDate')->nullable();
            $table->timestamp('startDate')->nullable();
            $table->timestamp('endDate')->nullable();
            $table->tinyInteger('publish')->default(1);
            $table->integer('order')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
