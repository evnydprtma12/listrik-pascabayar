<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Penggunaan;
use App\Models\Tarif;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagihanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dapat_membuat_data_tagihan()
    {
        $pelanggan = Pelanggan::factory()->create();
        $penggunaan = Penggunaan::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $tagihan = Tagihan::create([
            'penggunaan_id' => $penggunaan->id,
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Juli',
            'tahun' => 2025,
            'jumlah_meter' => 300,
            'status_tagihan' => 'belum dibayar',
        ]);

        $this->assertDatabaseHas('tagihan', [
            'id' => $tagihan->id,
            'pelanggan_id' => $pelanggan->id,
            'penggunaan_id' => $penggunaan->id,
        ]);
    }

    /** @test */
    public function tagihan_memiliki_relasi_dengan_pelanggan()
    {
        $pelanggan = Pelanggan::factory()->create();
        $tagihan = Tagihan::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $this->assertInstanceOf(Pelanggan::class, $tagihan->pelanggan);
        $this->assertEquals($pelanggan->id, $tagihan->pelanggan->id);
    }

    /** @test */
    public function tagihan_memiliki_relasi_dengan_penggunaan()
    {
        $penggunaan = Penggunaan::factory()->create();
        $tagihan = Tagihan::factory()->create(['penggunaan_id' => $penggunaan->id]);

        $this->assertInstanceOf(Penggunaan::class, $tagihan->penggunaan);
        $this->assertEquals($penggunaan->id, $tagihan->penggunaan->id);
    }

    /** @test */
    public function tagihan_dapat_mengambil_data_tarif_dari_pelanggan()
    {
        $tarif = Tarif::factory()->create(['tarif_perkwh' => 1500]);
        $pelanggan = Pelanggan::factory()->create(['tarif_id' => $tarif->id]);
        $tagihan = Tagihan::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $this->assertEquals($tarif->tarif_perkwh, $tagihan->tarif_data->tarif_perkwh);
    }

    /** @test */
    public function tagihan_dapat_menghitung_total_tagihan()
    {
        $tarif = Tarif::factory()->create(['tarif_perkwh' => 1500]);
        $pelanggan = Pelanggan::factory()->create(['tarif_id' => $tarif->id]);
        $tagihan = Tagihan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'jumlah_meter' => 100,
        ]);

        $this->assertEquals(150000, $tagihan->total_tagihan);
    }

    /** @test */
    public function total_tagihan_adalah_nol_jika_tarif_null()
    {
        $pelanggan = Pelanggan::factory()->create(['tarif_id' => null]);
        $tagihan = Tagihan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'jumlah_meter' => 100,
        ]);

        $this->assertEquals(0, $tagihan->total_tagihan);
    }
}
