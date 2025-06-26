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
        Schema::create('temporary_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('merk', 100)->nullable();
            $table->text('specification')->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->date('acquisition_date')->nullable();
            $table->bigInteger('acquisition_value')->default(0);
            $table->date('hand_over_date')->nullable();
            $table->string('nik', 50)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_inventories');
    }
};
