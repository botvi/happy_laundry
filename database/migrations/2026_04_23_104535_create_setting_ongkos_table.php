<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('setting_ongkos', function (Blueprint $table) {
            $table->id();
            $table->integer('harga_per_meter')->nullable();
            $table->string('latitude_lokasi_laundry')->nullable();
            $table->string('longitude_lokasi_laundry')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_ongkos');
    }
};
