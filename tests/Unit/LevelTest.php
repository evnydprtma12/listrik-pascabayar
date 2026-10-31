<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LevelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dapat_membuat_data_level()
    {
        $level = Level::create(['nama_level' => 'Admin']);

        $this->assertDatabaseHas('level', [
            'nama_level' => 'Admin',
        ]);
    }

    /** @test */
    public function level_memiliki_banyak_user()
    {
        // Buat level
        $level = Level::create(['nama_level' => 'Petugas']);

        // Buat user yang terkait level tersebut
        $user1 = User::factory()->create(['level_id' => $level->id]);
        $user2 = User::factory()->create(['level_id' => $level->id]);

        // Assert bahwa relasi berjalan dengan benar
        $this->assertInstanceOf(User::class, $level->users->first());
        $this->assertCount(2, $level->users);
        $this->assertTrue($level->users->contains($user1));
        $this->assertTrue($level->users->contains($user2));
    }
}
