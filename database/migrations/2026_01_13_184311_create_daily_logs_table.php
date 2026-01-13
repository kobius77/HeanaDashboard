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
    Schema::create('daily_logs', function (Blueprint $table) {
        $table->id();
        $table->date('log_date')->unique(); // The unique date constraint
        $table->integer('egg_count')->default(0);
        $table->text('notes')->nullable();
        $table->decimal('weather_temp_c', 4, 1)->nullable();
        $table->timestamps(); // Creates created_at AND updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};
