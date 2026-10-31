<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\Penggunaan;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PelangganDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function loginPelanggan()
    {
        $pelanggan = Pelanggan::factory()->create();
         /** @var \App\Models\User $pelanggan */
        $this->actingAs($pelanggan, 'pelanggan');

        return $pelanggan;
    }

    public function test_dashboard_index_can_be_accessed()
    {
        $pelanggan = $this->loginPelanggan();

        $response = $this->get(route('pelanggan.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('pelanggan', $pelanggan);
    }

    public function test_tagihan_saya_view_returns_correct_data()
    {
        $pelanggan = $this->loginPelanggan();

        Tagihan::factory()->count(3)->create(['pelanggan_id' => $pelanggan->id]);

        $response = $this->get(route('pelanggan.tagihan_saya'));

        $response->assertStatus(200);
        $response->assertViewHas('tagihans');
    }

    public function test_riwayat_pembayaran_view_shows_data()
    {
        $pelanggan = $this->loginPelanggan();

        $tagihan = Tagihan::factory()->create(['pelanggan_id' => $pelanggan->id]);
        Pembayaran::factory()->create(['pelanggan_id' => $pelanggan->id, 'tagihan_id' => $tagihan->id]);

        $response = $this->get(route('pelanggan.riwayat_pembayaran'));

        $response->assertStatus(200);
        $response->assertViewHas('riwayatPembayaran');
    }

    public function test_profil_saya_view_returns_pelanggan_data()
    {
        $pelanggan = $this->loginPelanggan();

        $response = $this->get(route('pelanggan.profil_saya'));

        $response->assertStatus(200);
        $response->assertViewHas('pelanggan');
    }

    public function test_update_profil_successfully()
    {
        $pelanggan = $this->loginPelanggan();

        $response = $this->put(route('pelanggan.update_profil'), [
            'nama_pelanggan' => 'Nama Baru',
            'username' => 'usernamebaru',
            'alamat' => 'Alamat baru',
        ]);

        $response->assertRedirect(route('pelanggan.profil_saya'));
        $this->assertDatabaseHas('pelanggan', [
            'id' => $pelanggan->id,
            'nama_pelanggan' => 'Nama Baru',
            'username' => 'usernamebaru',
        ]);
    }

    public function test_riwayat_penggunaan_returns_data()
    {
        $pelanggan = $this->loginPelanggan();

        Penggunaan::factory()->count(2)->create(['pelanggan_id' => $pelanggan->id]);

        $response = $this->get(route('pelanggan.riwayat_penggunaan'));

        $response->assertStatus(200);
        $response->assertViewHas('riwayatPenggunaan');
    }
}
