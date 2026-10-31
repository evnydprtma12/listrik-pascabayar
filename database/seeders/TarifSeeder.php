<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tarif;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        Tarif::firstOrCreate(['daya' => 450], ['tarif_perkwh' => 418.00]);
        Tarif::firstOrCreate(['daya' => 900], ['tarif_perkwh' => 1352.00]);
        Tarif::firstOrCreate(['daya' => 1300], ['tarif_perkwh' => 1444.70]);
        Tarif::firstOrCreate(['daya' => 2200], ['tarif_perkwh' => 1444.70]);
        Tarif::firstOrCreate(['daya' => 3500], ['tarif_perkwh' => 1444.70]);
    }
}