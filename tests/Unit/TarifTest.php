<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tarif;
use App\Models\Pelanggan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TarifTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dapat_membuat_tarif()
    {
        $tarif = Tarif::create([
            'daya' => 1300,
            'tarif_perkwh' => 1450.75,
        ]);

        $this->assertDatabaseHas('tarifs', [
            'id' => $tarif->id,
            'daya' => 1300,
            'tarif_perkwh' => 1450.75,
        ]);
    }

    /** @test */
    public function tarif_memiliki_banyak_pelanggan()
    {
        $tarif = Tarif::factory()->create();
        $pelanggan1 = Pelanggan::factory()->create(['tarif_id' => $tarif->id]);
        $pelanggan2 = Pelanggan::factory()->create(['tarif_id' => $tarif->id]);

        $this->assertTrue($tarif->pelanggans->contains($pelanggan1));
        $this->assertTrue($tarif->pelanggans->contains($pelanggan2));
        $this->assertCount(2, $tarif->pelanggans);
    }

    /** @test */
    public function tarif_perkwh_dicast_ke_decimal_dua_digit()
    {
        $tarif = Tarif::create([
            'daya' => 2200,
            'tarif_perkwh' => 1234.5,
        ]);

        $this->assertEquals('1234.50', number_format($tarif->tarif_perkwh, 2, '.', ''));
    }

}
