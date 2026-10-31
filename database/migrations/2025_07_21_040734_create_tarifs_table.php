<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id(); // Primary key otomatis 'id'
            $table->integer('daya')->unique(); // Daya, misal 900, 1300, 2200 VA
            $table->decimal('tarif_perkwh', 10, 2); // Tarif per kWh, misal 1444.70
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};