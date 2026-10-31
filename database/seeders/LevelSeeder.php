<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::firstOrCreate(['nama_level' => 'administrator']);
        Level::firstOrCreate(['nama_level' => 'petugas']);
    }
}