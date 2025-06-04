<?php
namespace Tests\Feature;

use App\IoC\IoC;
use Tests\TestCase;
use App\DTO\AgentMessageDTO;
use App\Commands\InterpretCommand;
use App\ThreadSafe\ThreadSafeCommandQueue;

class InterpretCommandText  extends TestCase
{
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
}