<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tarif;
use App\Models\Level;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Penggunaan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagihanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat level admin
        Level::factory()->create([
            'id' => 1,
            'nama_level' => 'Admin',
        ]);
    }

    protected function loginAsAdmin()
    {
        $admin = User::factory()->create(['level_id' => 1]);
         /** @var \App\Models\User $admin */
        $this->actingAs($admin);
        return $admin;
    }

    /** @test */
    public function admin_bisa_melihat_halaman_create_from_penggunaan()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('admin.tagihans.create_from_penggunaan'));

        $response->assertStatus(200);
        $response->assertViewIs('tagihan.create_from_penggunaan');
    }

    /** @test */
    public function admin_bisa_generate_tagihan_dari_penggunaan()
    {
        $this->loginAsAdmin();

        $tarif = Tarif::factory()->create();
        $pelanggan = Pelanggan::factory()->create(['tarif_id' => $tarif->id]);
        $penggunaan = Penggunaan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'bulan' => now()->format('F'),
            'tahun' => now()->year,
            'meter_awal' => 100,
            'meter_akhir' => 200,
        ]);

        $response = $this->post(route('admin.tagihans.generate'), [
            'penggunaan_ids' => [$penggunaan->id],
        ]);

        $response->assertRedirect(route('admin.tagihans.index'));

        $this->assertDatabaseHas('tagihan', [
            'penggunaan_id' => $penggunaan->id,
            'pelanggan_id' => $pelanggan->id,
            'jumlah_meter' => 100,
            'status_tagihan' => 'Belum Dibayar',
        ]);
    }

    /** @test */
    public function admin_bisa_melihat_halaman_edit_tagihan()
    {
        $this->loginAsAdmin();

        $tagihan = Tagihan::factory()->create();

        $response = $this->get(route('admin.tagihans.edit', $tagihan->id));

        $response->assertStatus(200);
        $response->assertViewIs('tagihan.edit');
    }

    /** @test */
    public function admin_bisa_update_status_tagihan()
    {
        $this->loginAsAdmin();

        $tagihan = Tagihan::factory()->create(['status_tagihan' => 'Belum Dibayar']);

        $response = $this->put(route('admin.tagihans.update', $tagihan->id), [
            'status_tagihan' => 'Sudah Dibayar',
        ]);

        $response->assertRedirect(route('admin.tagihans.index'));

        $this->assertDatabaseHas('tagihan', [
            'id' => $tagihan->id,
            'status_tagihan' => 'Sudah Dibayar',
        ]);
    }

    /** @test */
    public function admin_bisa_menghapus_tagihan()
    {
        $this->loginAsAdmin();

        $tagihan = Tagihan::factory()->create();

        $response = $this->delete(route('admin.tagihans.destroy', $tagihan->id));

        $response->assertRedirect(route('admin.tagihans.index'));

        $this->assertDatabaseMissing('tagihan', [
            'id' => $tagihan->id,
        ]);
    }
}
