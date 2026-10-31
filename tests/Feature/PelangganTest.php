<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PelangganTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function loginAsAdmin()
    {
        $adminLevel = Level::factory()->create(['id' => 1, 'nama_level' => 'Admin']);
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create(['level_id' => $adminLevel->id]);
        $this->actingAs($admin, 'web');
        return $admin;
    }

    protected function loginAsPetugas()
    {
        $petugasLevel = Level::factory()->create(['id' => 2, 'nama_level' => 'Petugas']);
        /** @var \App\Models\User $petugas */
        $petugas = User::factory()->create(['level_id' => $petugasLevel->id]);
        $this->actingAs($petugas, 'web');
        return $petugas;
    }

    /** @test */
    public function admin_bisa_melihat_daftar_pelanggan()
    {
        $this->loginAsAdmin();
        Pelanggan::factory()->count(3)->create();

        $response = $this->get('/admin/pelanggans');
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelanggans.index');
    }

    /** @test */
    public function petugas_bisa_melihat_daftar_pelanggan()
    {
        $this->loginAsPetugas();
        Pelanggan::factory()->count(3)->create();

        $response = $this->get('/petugas/pelanggans');
        $response->assertStatus(200);
        $response->assertViewIs('petugas.pelanggans.index');
    }

    /** @test */
    public function admin_dapat_membuka_form_tambah_pelanggan()
    {
        $this->loginAsAdmin();

        $response = $this->get('/admin/pelanggans/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.pelanggans.create');
    }

    /** @test */
    public function admin_dapat_menyimpan_pelanggan_baru()
    {
        $this->loginAsAdmin();

        $tarif = DB::table('tarifs')->insertGetId([
            'daya' => 1300,
            'tarif_perkwh' => 1352.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->post('/admin/pelanggans', [
            'tarif_id' => $tarif,
            'nama_pelanggan' => 'Contoh Pelanggan',
            'username' => 'pelangganbaru',
            'password' => 'passwordku',
            'alamat' => 'Jalan Hijau No. 123',
            'nomor_kwh' => '1234567890',
        ]);

        $response->assertRedirect('/admin/pelanggans');
        $this->assertDatabaseHas('pelanggan', ['username' => 'pelangganbaru']);
    }

    /** @test */
    public function admin_dapat_mengedit_dan_memperbarui_pelanggan()
    {
        $this->loginAsAdmin();

        $tarifId = DB::table('tarifs')->insertGetId([
            'daya' => 2200,
            'tarif_perkwh' => 1463.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $pelanggan = Pelanggan::factory()->create(['tarif_id' => $tarifId]);

        $responseEdit = $this->get("/admin/pelanggans/{$pelanggan->id}/edit");
        $responseEdit->assertStatus(200);
        $responseEdit->assertViewIs('admin.pelanggans.edit');

        $responseUpdate = $this->put("/admin/pelanggans/{$pelanggan->id}", [
            'tarif_id' => $tarifId,
            'nama_pelanggan' => 'Nama Update',
            'username' => $pelanggan->username,
            'alamat' => 'Alamat Update',
            'nomor_kwh' => $pelanggan->nomor_kwh,
        ]);

        $responseUpdate->assertRedirect('/admin/pelanggans');
        $this->assertDatabaseHas('pelanggan', ['nama_pelanggan' => 'Nama Update']);
    }

    /** @test */
    public function admin_dapat_menghapus_pelanggan()
    {
        $this->loginAsAdmin();

        $pelanggan = Pelanggan::factory()->create();

        $response = $this->delete("/admin/pelanggans/{$pelanggan->id}");
        $response->assertRedirect('/admin/pelanggans');
        $this->assertDatabaseMissing('pelanggan', ['id' => $pelanggan->id]);
    }

    /** @test */
    public function petugas_tidak_boleh_akses_form_tambah()
    {
        $this->loginAsPetugas();

        $response = $this->get(route('petugas.pelanggans.create'));

        $response->assertStatus(403);
    }
}
