<?php

namespace Tests\Feature;

use App\Commands\InterpretCommand;
use App\DTO\AgentMessageDTO;
use App\ThreadSafe\ThreadSafeCommandQueue;
use Tests\IoC\TestIoCProvider;
use Tests\TestCase;

class GameEndpointTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        TestIoCProvider::registerTestDependencies();
    }

    public function test_command_adding_to_queue()
    {
        $queue = new ThreadSafeCommandQueue();

        \App\IoC\IoC::Resolve('IoC.Register', 'GameCommandQueue', function () use ($queue) {
            return $queue;
        }, true);

        $message = new AgentMessageDTO(
            gameId: 'game1',
            objectId: 'obj1',
            operationId: 'move',
            args: ['velocity' => 5]
        );

        $command = new InterpretCommand($message, 'game1');
        $command->execute();

        $this->assertEquals(1, $queue->count());
    }

    public function test_agent_message_handling()
    {
        $response = $this->postJson('/api/agent/message', [
            'gameId' => 'game1',
            'objectId' => 'obj1',
            'operationId' => 'move',
            'args' => ['velocity' => 5]
        ]);

        $response->assertStatus(200)
            ->assertJson(['status' => 'message queued']);
    }
}