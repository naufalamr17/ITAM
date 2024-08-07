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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->nullable(); // Kode Aset
            $table->string('location');
            $table->string('description');
            $table->string('merk');
            $table->string('type');
            $table->string('specification'); 
            $table->string('serial_number')->nullable(); 
            $table->string('os');
            $table->string('installed_apps')->nullable();
            $table->string('acquisition_date'); 
            $table->string('disposal_date')->nullable(); 
            $table->integer('useful_life')->default(4);
            $table->bigInteger('acquisition_value')->default(0);
            $table->enum('status', ['Good', 'Breakdown', 'Repair', 'Waiting Dispose', 'Dispose'])->default('Good');
            $table->string('hand_over_date')->nullable();
            $table->string('nik')->nullable();
            $table->string('user')->nullable();
            $table->string('job_position')->nullable();
            $table->string('dept')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
