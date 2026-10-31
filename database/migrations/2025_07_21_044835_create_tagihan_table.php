<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id(); // Primary key 'id'
            $table->foreignId('penggunaan_id')->unique()->constrained('penggunaan')->onDelete('cascade'); // Foreign key ke tabel 'penggunaan' (unique karena 1 penggunaan -> 1 tagihan)
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade'); // Foreign key ke tabel 'pelanggan'
            $table->string('bulan', 20);
            $table->integer('tahun');
            $table->integer('jumlah_meter');
            $table->enum('status_tagihan', ['Belum Dibayar', 'Sudah Dibayar'])->default('Belum Dibayar'); // Status tagihan
            $table->timestamps();

            // Indeks untuk mempercepat pencarian
            $table->index(['pelanggan_id', 'tahun', 'bulan']);
            $table->index('status_tagihan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};