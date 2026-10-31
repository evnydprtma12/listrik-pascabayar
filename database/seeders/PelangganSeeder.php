<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Hash;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        Pelanggan::create([
             'nama_pelanggan' => 'JhonDoi',
            'username'       => 'JhonDoi11',
            'password'       => Hash::make('secret'),
            'alamat'         => 'Jl. Sudirman',
            'nomor_kwh'      => '12345214126',
            'tarif_id'       => 1 // id tarif dari seeder
        ]);
    }
}