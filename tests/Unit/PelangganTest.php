<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Pelanggan;
use App\Models\Tarif;
use App\Models\Penggunaan;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PelangganTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dapat_membuat_data_pelanggan()
    {
        $pelanggan = Pelanggan::create([
            'nama_pelanggan' => 'Budi',
            'username' => 'budi123',
            'password' => bcrypt('password'),
            'alamat' => 'Jl. Merdeka',
            'nomor_kwh' => '1234567890',
        ]);

        $this->assertDatabaseHas('pelanggan', [
            'nama_pelanggan' => 'Budi',
            'username' => 'budi123',
        ]);
    }

    /** @test */
    public function pelanggan_dapat_terkait_dengan_tarif()
    {
        $tarif = Tarif::factory()->create();
        $pelanggan = Pelanggan::factory()->create(['tarif_id' => $tarif->id]);

        $this->assertNotNull($pelanggan->tarifs);
        $this->assertEquals($tarif->id, $pelanggan->tarifs->id);
    }

    /** @test */
    public function pelanggan_memiliki_banyak_penggunaan()
    {
        $pelanggan = Pelanggan::factory()->create();
        $penggunaan1 = Penggunaan::factory()->create(['pelanggan_id' => $pelanggan->id]);
        $penggunaan2 = Penggunaan::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $this->assertCount(2, $pelanggan->pengguna);
        $this->assertTrue($pelanggan->pengguna->contains($penggunaan1));
    }

    /** @test */
    public function pelanggan_memiliki_banyak_pembayaran()
    {
        $pelanggan = Pelanggan::factory()->create();
        $pembayaran1 = Pembayaran::factory()->create(['pelanggan_id' => $pelanggan->id]);
        $pembayaran2 = Pembayaran::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $this->assertCount(2, $pelanggan->pembayaran);
        $this->assertTrue($pelanggan->pembayaran->contains($pembayaran1));
    }
}
