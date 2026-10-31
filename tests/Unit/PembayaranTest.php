<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Level;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dapat_membuat_data_pembayaran()
    {
        // Buat level dengan id 1 terlebih dahulu
        Level::factory()->create(['id' => 1]);

        $user = User::factory()->create(['level_id' => 1]);
        $pelanggan = Pelanggan::factory()->create();
        $tagihan = Tagihan::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $pembayaran = Pembayaran::create([
            'user_id' => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'tagihan_id' => $tagihan->id,
            'tanggal_pembayaran' => now(),
            'biaya_admin' => 2500,
            'total_bayar' => 50000,
        ]);

        $this->assertDatabaseHas('pembayaran', [
            'id' => $pembayaran->id,
            'user_id' => $user->id,
            'pelanggan_id' => $pelanggan->id,
            'tagihan_id' => $tagihan->id,
        ]);
    }

    /** @test */
    public function pembayaran_memiliki_relasi_dengan_user()
    {
        Level::factory()->create(['id' => 1]);
        $user = User::factory()->create(['level_id' => 1]);
        $pembayaran = Pembayaran::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $pembayaran->user);
    }

    /** @test */
    public function pembayaran_memiliki_relasi_dengan_pelanggan()
    {
        $pelanggan = Pelanggan::factory()->create();
        $pembayaran = Pembayaran::factory()->create(['pelanggan_id' => $pelanggan->id]);

        $this->assertInstanceOf(Pelanggan::class, $pembayaran->pelanggan);
    }

    /** @test */
    public function pembayaran_memiliki_relasi_dengan_tagihan()
    {
        $tagihan = Tagihan::factory()->create();
        $pembayaran = Pembayaran::factory()->create(['tagihan_id' => $tagihan->id]);

        $this->assertInstanceOf(Tagihan::class, $pembayaran->tagihan);
    }
}
