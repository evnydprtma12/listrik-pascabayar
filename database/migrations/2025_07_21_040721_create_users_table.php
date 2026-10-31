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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Kolom 'id' sebagai primary key
            $table->string('nama_user', 100);
            $table->string('username', 50)->unique();
            $table->string('password', 100);
            $table->foreignId('level_id')->nullable()->constrained('level')->onDelete('set null'); // level_id
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};