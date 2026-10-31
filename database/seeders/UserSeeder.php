<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminLevel = Level::where('nama_level', 'administrator')->first();
        $pelangganLevel = Level::where('nama_level', 'petugas')->first();

        User::firstOrCreate(
            ['username' => 'admin'],
            [
                
                'nama_user' => 'Administrator Utama',
                'password' => Hash::make('password'), // Ganti dengan password yang kuat!
                'level_id' => $adminLevel->id,
            ]
        );

        User::firstOrCreate(
            ['username' => 'petugas'],
            [
                'nama_user' => 'Udin Gacha',
                'password' => Hash::make('password'),
                'level_id' => $pelangganLevel->id,
            ]
        );
    }
}