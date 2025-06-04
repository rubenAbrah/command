<?php

namespace App\DTO;

class AgentMessageDTO
{
    public function __construct(
        public string $gameId,
        public string $objectId,
        public string $operationId,
        public array $args = []
    ) {}
}