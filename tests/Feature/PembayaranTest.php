<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tagihan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PembayaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat level Administrator dengan ID 1
        Level::factory()->create([
            'id' => 1,
            'nama_level' => 'Administrator',
        ]);
    }

    protected function loginAsAdmin()
    {
        $admin = User::factory()->create(['level_id' => 1]);
         /** @var \App\Models\User $admin */
        $this->actingAs($admin, 'web');
        return $admin;
    }

    public function test_index_page_shows_pembayarans()
    {
        $this->loginAsAdmin();

        Pembayaran::factory()->count(3)->create();

        $response = $this->get(route('admin.pembayarans.index'));
        $response->assertStatus(200);
        $response->assertViewHas('pembayarans');
    }

    public function test_create_page_shows_unpaid_tagihans()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('admin.pembayarans.create'));
        $response->assertStatus(200);
        $response->assertViewHas('tagihansBelumLunas');
    }

    public function test_store_pembayaran_successfully()
    {
        $admin = $this->loginAsAdmin();

        $pelanggan = Pelanggan::factory()->create();
        $tagihan = Tagihan::factory()->create([
            'pelanggan_id' => $pelanggan->id,
            'status_tagihan' => 'Belum Dibayar',
        ]);

        $response = $this->post(route('admin.pembayarans.store'), [
            'tagihan_id' => $tagihan->id,
            'tanggal_pembayaran' => now()->toDateString(),
            'biaya_admin' => 1000,
        ]);

        $response->assertRedirect(route('admin.pembayarans.index'));
        $this->assertDatabaseHas('pembayaran', [
            'tagihan_id' => $tagihan->id,
            'pelanggan_id' => $pelanggan->id,
            'biaya_admin' => 1000,
        ]);
        $this->assertDatabaseHas('tagihan', [
            'id' => $tagihan->id,
            'status_tagihan' => 'Sudah Dibayar',
        ]);
    }

    public function test_update_pembayaran_successfully()
    {
        $this->loginAsAdmin();

        $pembayaran = Pembayaran::factory()->create([
            'biaya_admin' => 0,
            'total_bayar' => 10000,
        ]);

        $response = $this->put(route('admin.pembayarans.update', $pembayaran->id), [
            'tanggal_pembayaran' => now()->toDateString(),
            'biaya_admin' => 2000,
        ]);

        $response->assertRedirect(route('admin.pembayarans.index'));
        $this->assertDatabaseHas('pembayaran', [
            'id' => $pembayaran->id,
            'biaya_admin' => 2000,
        ]);
    }

    public function test_delete_pembayaran_sets_tagihan_to_unpaid()
    {
        $this->loginAsAdmin();

        $tagihan = Tagihan::factory()->create([
            'status_tagihan' => 'Sudah Dibayar',
        ]);

        $pembayaran = Pembayaran::factory()->create([
            'tagihan_id' => $tagihan->id,
        ]);

        $response = $this->delete(route('admin.pembayarans.destroy', $pembayaran->id));
        $response->assertRedirect(route('admin.pembayarans.index'));

        $this->assertDatabaseMissing('pembayaran', ['id' => $pembayaran->id]);
        $this->assertDatabaseHas('tagihan', [
            'id' => $tagihan->id,
            'status_tagihan' => 'Belum Dibayar',
        ]);
    }
}
