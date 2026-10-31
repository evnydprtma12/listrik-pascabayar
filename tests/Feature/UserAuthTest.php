<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Level;
use App\Models\Pelanggan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup level untuk user (admin & petugas)
        Level::factory()->administrator()->create(['id' => 1]);
        Level::factory()->petugas()->create(['id' => 2]);
    }

    public function test_login_form_is_displayed(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_admin_user_can_login_with_correct_credentials(): void
    {
        /** @var \App\Models\User $adminUser */
        $adminUser = User::factory()->create([
            'username' => 'admin_test',
            'password' => bcrypt('password123'),
            'level_id' => 1,
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated('web');
        $this->assertEquals($adminUser->id, auth('web')->id());
    }

    public function test_petugas_user_can_login_with_correct_credentials(): void
    {
        /** @var \App\Models\User $petugasUser */
        $petugasUser = User::factory()->create([
            'username' => 'petugas_test',
            'password' => bcrypt('password123'),
            'level_id' => 2,
        ]);

        $response = $this->post('/login', [
            'username' => 'petugas_test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/petugas/dashboard');
        $this->assertAuthenticated('web');
        $this->assertEquals($petugasUser->id, auth('web')->id());
    }

    public function test_pelanggan_can_login_with_correct_credentials(): void
    {
        /** @var \App\Models\Pelanggan $pelanggan */
        $pelanggan = Pelanggan::factory()->create([
            'username' => 'pelanggan_test',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'pelanggan_test',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/pelanggan/dashboard');
        $this->assertAuthenticated('pelanggan');
        $this->assertEquals($pelanggan->id, auth('pelanggan')->id());
    }

    public function test_web_user_can_logout(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'username' => 'admin_to_logout',
            'password' => bcrypt('password123'),
            'level_id' => 1,
        ]);

        $this->actingAs($user, 'web');

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest('web');
    }

    public function test_pelanggan_can_logout(): void
    {
        /** @var \App\Models\Pelanggan $pelanggan */
        $pelanggan = Pelanggan::factory()->create([
            'username' => 'pelanggan_to_logout',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($pelanggan, 'pelanggan');

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest('pelanggan');
    }
}
