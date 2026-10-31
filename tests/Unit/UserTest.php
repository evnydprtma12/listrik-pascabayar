<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Level;
use App\Models\Pembayaran;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_dapat_dibuat_dengan_akun_dasar()
    {
        $user = User::factory()->create([
            'nama_user' => 'Evan',
            'username' => 'evan123',
            'password' => bcrypt('password'),
            'level_id' => null,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'username' => 'evan123',
        ]);
    }

    /** @test */
    public function user_memiliki_relasi_dengan_level()
    {
        $level = Level::factory()->create(['nama_level' => 'Admin']);
        $user = User::factory()->create(['level_id' => $level->id]);

        $this->assertEquals('Admin', $user->level->nama_level);
    }

    /** @test */
    public function user_memiliki_banyak_pembayaran()
    {
        $user = User::factory()->create();
        $pembayaran1 = Pembayaran::factory()->create(['user_id' => $user->id]);
        $pembayaran2 = Pembayaran::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($user->pembayaran->contains($pembayaran1));
        $this->assertTrue($user->pembayaran->contains($pembayaran2));
        $this->assertCount(2, $user->pembayaran);
    }

    /** @test */
    public function password_dihidden_dari_serialisasi()
    {
        $user = User::factory()->create([
            'password' => bcrypt('rahasia123'),
        ]);

        $array = $user->toArray();
        $this->assertArrayNotHasKey('password', $array);
    }
}
