<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Level;
use App\Models\Pelanggan;
use App\Models\Penggunaan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PenggunaanTest extends TestCase
{
    use RefreshDatabase;

    protected function loginAsAdmin(): User
    {
        // Pastikan level Admin tersedia
        Level::factory()->create([
            'id' => 1,
            'nama_level' => 'Admin',
        ]);

        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'username' => 'admin_penggunaan_test',
            'password' => bcrypt('password123'),
            'level_id' => 1,
        ]);

        // Pastikan actingAs menerima instance tunggal User
        $this->actingAs($admin, 'web');

        return $admin;
    }

    public function test_admin_dapat_melihat_halaman_index_penggunaan()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('admin.penggunaans.index'));
        $response->assertStatus(200);
        $response->assertViewIs('penggunaan.index');
    }

    public function test_admin_dapat_menambahkan_data_penggunaan()
    {
        $this->loginAsAdmin();

        $pelanggan = Pelanggan::factory()->create();

        $data = [
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Januari',
            'tahun' => 2025,
            'meter_awal' => 100,
            'meter_akhir' => 150,
        ];

        $response = $this->post(route('admin.penggunaans.store'), $data);

        $response->assertRedirect(route('admin.penggunaans.index'));
        $this->assertDatabaseHas('penggunaan', $data);
    }

    public function test_admin_tidak_dapat_menambahkan_penggunaan_duplikat()
    {
        $this->loginAsAdmin();

        $pelanggan = Pelanggan::factory()->create();

        Penggunaan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Januari',
            'tahun' => 2025,
            'meter_awal' => 100,
            'meter_akhir' => 150,
        ]);

        $response = $this->post(route('admin.penggunaans.store'), [
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Januari',
            'tahun' => 2025,
            'meter_awal' => 200,
            'meter_akhir' => 250,
        ]);

        $response->assertSessionHasErrors(); // Bisa ditambahkan field spesifik jika validasi eksplisit
    }

    public function test_admin_dapat_memperbarui_data_penggunaan()
    {
        $this->loginAsAdmin();

        $pelanggan = Pelanggan::factory()->create();

        $penggunaan = Penggunaan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Februari',
            'tahun' => 2025,
            'meter_awal' => 120,
            'meter_akhir' => 170,
        ]);

        $response = $this->put(route('admin.penggunaans.update', $penggunaan), [
            'pelanggan_id' => $pelanggan->id,
            'bulan' => 'Februari',
            'tahun' => 2025,
            'meter_awal' => 130,
            'meter_akhir' => 180,
        ]);

        $response->assertRedirect(route('admin.penggunaans.index'));
        $this->assertDatabaseHas('penggunaan', [
            'id' => $penggunaan->id,
            'meter_awal' => 130,
            'meter_akhir' => 180,
        ]);
    }

    public function test_admin_dapat_menghapus_data_penggunaan()
    {
        $this->loginAsAdmin();

        $penggunaan = Penggunaan::factory()->create();

        $response = $this->delete(route('admin.penggunaans.destroy', $penggunaan));

        $response->assertRedirect(route('admin.penggunaans.index'));
        $this->assertDatabaseMissing('penggunaan', ['id' => $penggunaan->id]);
    }
}
