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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('paket_laundry_id')->constrained('paket_laundries')->onDelete('cascade')->onUpdate('cascade');
            $table->string('dijemput')->default('tidak');
            $table->string('diantar')->default('tidak');
            $table->string('latitude_antar_jemput')->nullable();
            $table->string('longitude_antar_jemput')->nullable();
            $table->integer('ongkir_antar_jemput')->nullable();
            $table->integer('jumlah_kilogram')->nullable();
            $table->string('gambar_bukti_timbangan')->nullable();
            $table->integer('total_harga')->nullable();
            $table->string('status_pesanan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
