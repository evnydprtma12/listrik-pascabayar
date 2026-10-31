<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_pelanggan_table.php

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
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarif_id')->nullable()->constrained('tarifs'); // Menunjuk ke 'tarifs'
            $table->string('nama_pelanggan', 100);
            $table->string('username', 100)->unique();
            $table->string('password', 100);
            $table->string('alamat', 200);
            $table->string('nomor_kwh', 50)->unique(); // <-- Ganti 'daya' menjadi 'nomor_kwh' dan pastikan unique
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};