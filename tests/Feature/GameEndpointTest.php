<?php

namespace Tests\Feature;

use App\Services\AuthService;
use Tests\TestCase;

class GameEndpointTest extends TestCase
{
    public function test_authenticated_message_handling()
    {
        $authService = app(AuthService::class);
        $token = $authService->generateToken('user1', 'game1');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/game/message', [
                    'objectId' => 'obj1',
                    'operationId' => 'move',
                    'args' => ['velocity' => 5],
                ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'message queued']);
    }

    public function test_unauthenticated_message_handling()
    {
        $response = $this->postJson('/api/game/message', [
            'objectId' => 'obj1',
            'operationId' => 'move',
            'args' => ['velocity' => 5],
        ]);

        $response->assertStatus(401)
            ->assertJson(['error' => 'Token required']);
    }
}