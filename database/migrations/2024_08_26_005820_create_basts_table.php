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
        Schema::create('basts', function (Blueprint $table) {
            $table->id();
            $table->string('no');
            $table->date('date');
            $table->string('pic');
            $table->string('nik_user');
            $table->string('jenis_barang');
            $table->string('merk');
            $table->string('type');
            $table->string('serial_number');
            $table->string('color');
            $table->text('spesifikasi')->nullable(); // Spesifikasi could be a longer text, hence text type
            $table->string('scan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basts');
    }
};
