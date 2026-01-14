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
        Schema::table('flock_records', function (Blueprint $table) {
            $table->unsignedInteger('cock')->default(0)->after('henopaused_hens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flock_records', function (Blueprint $table) {
            $table->dropColumn('cock');
        });
    }
};
