<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Level;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected function loginAsAdmin()
    {
        $adminLevel = Level::factory()->create(['id' => 1, 'nama_level' => 'Admin']);
        /** @var \App\Models\User $admin */
        $admin = User::factory()->create([
            'level_id' => $adminLevel->id,
        ]);
        $this->actingAs($admin, 'web');
        return $admin;
    }

    /** @test */
    public function admin_dapat_melihat_daftar_user()
    {
        $this->loginAsAdmin();
        User::factory()->count(3)->create();

        $response = $this->get(route('admin.users.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.user.index');
    }

    /** @test */
    public function admin_dapat_menambahkan_user_baru()
    {
        $this->loginAsAdmin();

        $level = Level::factory()->create();
        $response = $this->post(route('admin.users.store'), [
            'nama_user' => 'Test User',
            'username' => 'testuser',
            'password' => 'password123',
            'level_id' => $level->id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'level_id' => $level->id,
        ]);
    }

    /** @test */
    public function admin_dapat_mengupdate_user_tanpa_password()
    {
        $this->loginAsAdmin();

        $level = Level::factory()->create();
        $user = User::factory()->create(['level_id' => $level->id]);

        $response = $this->put(route('admin.users.update', $user->id), [
            'nama_user' => 'Updated User',
            'username' => 'updatedusername',
            'level_id' => $level->id,
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'nama_user' => 'Updated User',
            'username' => 'updatedusername',
        ]);
    }

    /** @test */
    public function admin_dapat_mengupdate_user_dengan_password()
    {
        $this->loginAsAdmin();

        $level = Level::factory()->create();
        $user = User::factory()->create(['level_id' => $level->id]);

        $response = $this->put(route('admin.users.update', $user->id), [
            'nama_user' => 'Updated User',
            'username' => 'updatedusername',
            'level_id' => $level->id,
            'password' => 'newpassword123',
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /** @test */
    public function admin_tidak_dapat_menghapus_dirinya_sendiri()
    {
        $admin = $this->loginAsAdmin();

        $response = $this->delete(route('admin.users.destroy', $admin->id));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    /** @test */
    public function admin_dapat_menghapus_user_lain()
    {
        $this->loginAsAdmin();

        $level = Level::factory()->create();
        $user = User::factory()->create(['level_id' => $level->id]);

        $response = $this->delete(route('admin.users.destroy', $user->id));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
