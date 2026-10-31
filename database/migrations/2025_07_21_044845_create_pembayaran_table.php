<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id(); // Primary key 'id'
            $table->foreignId('tagihan_id')->constrained('tagihan')->onDelete('cascade'); // Foreign key ke tabel 'tagihan'
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade'); // Foreign key ke tabel 'pelanggan'
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Foreign key ke tabel 'user' (siapa yang membayar)
            $table->date('tanggal_pembayaran');
            $table->decimal('biaya_admin', 12, 2)->default(0.00);
            $table->decimal('total_bayar', 12, 2); // Total pembayaran termasuk biaya admin
            $table->timestamps();

            // Indeks untuk mempercepat pencarian
            $table->index(['pelanggan_id', 'tanggal_pembayaran']);
            $table->index('tagihan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};