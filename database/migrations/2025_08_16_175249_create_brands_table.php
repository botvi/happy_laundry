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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('logo_brand')->nullable()->comment('Nama file logo brand');
            $table->text('link_brand')->comment('URL lengkap brand');
            $table->enum('status_brand', ['active', 'inactive'])->default('active')->comment('Status brand: active/inactive');
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index('status_brand');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
