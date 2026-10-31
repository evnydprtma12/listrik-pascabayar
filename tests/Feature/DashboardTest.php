<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test tampilan dashboard admin berhasil dimuat.
     */
    public function test_admin_dapat_mengakses_dashboard()
    {
        // Buat level admin (id = 1)
        $levelAdmin = Level::factory()->create([
            'id' => 1,
            'nama_level' => 'Admin'
        ]);

        // Buat user admin dengan level_id = 1
        $admin = User::factory()->create([
            'level_id' => $levelAdmin->id,
        ]);
         /** @var \App\Models\User $admin */
        $this->actingAs($admin);

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHasAll([
            'totalPenggunaan',
            'totalTagihanBelumLunas',
            'totalPembayaranBulanIni',
            'latestPayments',
        ]);
    }

    /**
     * Test tampilan dashboard petugas berhasil dimuat.
     */
    public function test_petugas_dapat_mengakses_dashboard()
    {
        // Buat level petugas (id = 2)
        $levelPetugas = Level::factory()->create([
            'id' => 2,
            'nama_level' => 'Petugas'
        ]);

        // Buat user petugas dengan level_id = 2
        $petugas = User::factory()->create([
            'level_id' => $levelPetugas->id,
        ]);
         /** @var \App\Models\User $petugas */
        $this->actingAs($petugas);

        $response = $this->get('/petugas/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('petugas.dashboard');
        $response->assertViewHasAll([
            'totalPelanggan',
            'totalTagihanBelumLunas',
            'totalPenggunaanBulanIni',
            'latestPenggunaans',
        ]);
    }
}
