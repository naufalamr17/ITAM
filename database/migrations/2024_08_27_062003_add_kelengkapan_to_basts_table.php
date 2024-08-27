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
        Schema::table('basts', function (Blueprint $table) {
            Schema::table('basts', function (Blueprint $table) {
                $table->string('kelengkapan')->nullable()->after('spesifikasi');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('basts', function (Blueprint $table) {
            $table->dropColumn('kelengkapan');
        });
    }
};
