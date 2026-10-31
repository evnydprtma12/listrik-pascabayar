<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penggunaan', function (Blueprint $table) {
            $table->id(); // Primary key otomatis 'id'
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade'); // Foreign key ke tabel 'pelanggan'
            $table->string('bulan', 20); // Contoh: 'Januari'
            $table->integer('tahun'); // Contoh: 2023
            $table->integer('meter_awal');
            $table->integer('meter_akhir');
            $table->timestamps();
 // Tambahkan unique constraint agar satu pelanggan hanya punya satu record penggunaan per bulan/tahun
            $table->unique(['pelanggan_id', 'bulan', 'tahun']);
            $table->index(['pelanggan_id', 'bulan', 'tahun']); // Indeks untuk performa
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penggunaan');
    }
};