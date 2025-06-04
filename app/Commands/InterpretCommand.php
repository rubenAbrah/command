<?php

namespace App\Commands;

use App\DTO\AgentMessageDTO;
use App\IoC\IoC;

class InterpretCommand extends Command
{
    public function __construct(
        private AgentMessageDTO $message,
        private string $gameQueueId
    ) {}

    public function execute()
    {
        \Log::debug('InterpretCommand executing', [
            'gameId' => $this->gameQueueId,
            'operation' => $this->message->operationId
        ]);
        
        $gameObject = IoC::Resolve("GameObject", $this->message->objectId);
        
        foreach ($this->message->args as $key => $value) {
            IoC::Resolve("SetProperty", $gameObject, $key, $value);
        }
        
        $command = IoC::Resolve("OperationCommand", $this->message->operationId, $gameObject);
        
        $queue = IoC::Resolve("GameCommandQueue", $this->gameQueueId);
        $queue->add($command);
        
        \Log::debug('Command added to queue', [
            'command' => get_class($command),
            'queueSize' => $queue->count()
        ]);
    }
}