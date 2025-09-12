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
        Schema::create('rfms', function (Blueprint $table) {
            $table->id();
            $table->string('no_rfm')->unique(); // Nomor RFM, wajib unik
            $table->text('deskripsi'); // Deskripsi RFM
            $table->string('dokumen_pdf'); // Menyimpan path dokumen PDF
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfms');
    }
};
