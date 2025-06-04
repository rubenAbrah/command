<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Tests\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->runDatabaseMigrations();
    }
    public function test_create_game()
    {
        $response = $this->postJson('/api/auth/create-game', [
            'participants' => ['user1', 'user2', 'user3'],
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['game_id']);

        $this->assertDatabaseHas('game_participants', [
            'game_id' => $response->json('game_id'),
        ]);
    }

    public function test_get_token_for_participant()
    {
        $gameId = DB::table('game_participants')->insertGetId([
            'game_id' => 'test_game',
            'participants' => json_encode(['user1', 'user2']),
            'created_at' => now(),
        ]);
        $response = $this->postJson('/api/auth/token', [
            'user_id' => 'user1',
            'game_id' => 'test_game',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    public function test_get_token_for_non_participant()
    {
        $gameId = DB::table('game_participants')->insertGetId([
            'game_id' => 'test_game',
            'participants' => json_encode(['user1', 'user2']),
            'created_at' => now(),
        ]);

        $response = $this->postJson('/api/auth/token', [
            'user_id' => 'user3',
            'game_id' => 'test_game',
        ]);

        $response->assertStatus(403);
    }
}
