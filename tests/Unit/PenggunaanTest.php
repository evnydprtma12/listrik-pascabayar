<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Penggunaan;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenggunaanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dapat_membuat_data_penggunaan()
    {
        $pelanggan = Pelanggan::factory()->create();

        $penggunaan = Penggunaan::create([
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Juli',
            'tahun' => 2025,
            'meter_awal' => 1000,
            'meter_akhir' => 1200,
        ]);

        $this->assertDatabaseHas('penggunaan', [
            'id' => $penggunaan->id,
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Juli',
            'tahun' => 2025,
        ]);
    }

    /** @test */
    public function penggunaan_memiliki_relasi_dengan_pelanggan()
    {
        $pelanggan = Pelanggan::factory()->create();
        $penggunaan = Penggunaan::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $this->assertInstanceOf(Pelanggan::class, $penggunaan->pelanggan);
        $this->assertEquals($pelanggan->id, $penggunaan->pelanggan->id);
    }

    /** @test */
    public function penggunaan_memiliki_relasi_dengan_tagihan()
    {
        $penggunaan = Penggunaan::factory()->create();
        $tagihan = Tagihan::factory()->create(['penggunaan_id' => $penggunaan->id]);

        $this->assertInstanceOf(Tagihan::class, $penggunaan->tagihan);
        $this->assertEquals($tagihan->id, $penggunaan->tagihan->id);
    }

    /** @test */
    public function dapat_menghitung_jumlah_meter()
    {
        $penggunaan = Penggunaan::factory()->create([
            'meter_awal' => 200,
            'meter_akhir' => 500,
        ]);

        $this->assertEquals(300, $penggunaan->jumlah_meter);
    }
}
