<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Level;
use App\Models\Tarif;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TarifTest extends TestCase
{
    use RefreshDatabase;

    protected function loginAsAdmin()
    {
        $adminLevel = Level::factory()->create(['id' => 1, 'nama_level' => 'Admin']);
        $admin = User::factory()->create(['level_id' => $adminLevel->id]);
         /** @var \App\Models\User $admin */
        $this->actingAs($admin, 'web');
        return $admin;
    }

    public function test_admin_dapat_melihat_daftar_tarif()
    {
        $this->loginAsAdmin();
        Tarif::factory()->count(3)->create();

        $response = $this->get(route('admin.tarifs.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tarifs.index');
    }

    public function test_admin_dapat_membuka_form_create_tarif()
    {
        $this->loginAsAdmin();
        $response = $this->get(route('admin.tarifs.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tarifs.create');
    }

    public function test_admin_dapat_menyimpan_tarif_baru()
    {
        $this->loginAsAdmin();

        $data = [
            'daya' => 1300,
            'tarif_perkwh' => 1500,
        ];

        $response = $this->post(route('admin.tarifs.store'), $data);

        $response->assertRedirect(route('admin.tarifs.index'));
        $this->assertDatabaseHas('tarifs', $data);
    }

    public function test_admin_dapat_mengedit_tarif()
    {
        $this->loginAsAdmin();
        $tarif = Tarif::factory()->create();

        $response = $this->get(route('admin.tarifs.edit', $tarif->id));
        $response->assertStatus(200);
        $response->assertViewIs('admin.tarifs.edit');
    }

    public function test_admin_dapat_mengupdate_tarif()
    {
        $this->loginAsAdmin();
        $tarif = Tarif::factory()->create([
            'daya' => 900,
            'tarif_perkwh' => 1350,
        ]);

        $dataBaru = [
            'daya' => 1300,
            'tarif_perkwh' => 1450,
        ];

        $response = $this->put(route('admin.tarifs.update', $tarif->id), $dataBaru);
        $response->assertRedirect(route('admin.tarifs.index'));

        $this->assertDatabaseHas('tarifs', $dataBaru);
    }

    public function test_admin_dapat_menghapus_tarif()
    {
        $this->loginAsAdmin();
        $tarif = Tarif::factory()->create();

        $response = $this->delete(route('admin.tarifs.destroy', $tarif->id));
        $response->assertRedirect(route('admin.tarifs.index'));

        $this->assertDatabaseMissing('tarifs', ['id' => $tarif->id]);
    }
}
