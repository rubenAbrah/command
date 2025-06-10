<?php

namespace App\Handlers;

use App\Game\Interfaces\GameObject;
use App\Game\Interfaces\Movable;

class OrderHandler
{
    private array $gameObjects = [];
    private $commands = [];

    public function __construct(
        private readonly \App\Commands\StartMoveCommand $startMoveCommand
    ) {
    }

    public function registerObject(GameObject $gameObject): void
    {
        $this->gameObjects[$gameObject->getId()] = $gameObject;
    }

    public function handleOrder(array $order, string $currentPlayerId): void
    {
        $id = $order['id'] ?? null;
        $action = $order['action'] ?? null;

        if (!isset($this->gameObjects[$id])) {
            throw new \Exception("Object with ID '$id' not found.");
        }

        $gameObject = $this->gameObjects[$id];

        if ($gameObject->getPlayerId() !== $currentPlayerId) {
            throw new \Exception("Cannot give order to another player's object.");
        }

        switch ($action) {
            case 'StartMove':
                $velocity = $order['initialVelocity'] ?? 0;
                $this->startMove($gameObject, $velocity);
                break;
            default:
                throw new \Exception("Unknown action: $action");
        }
    }

    private function startMove(GameObject $gameObject, float $velocity): void
    {
        if (!$gameObject instanceof Movable) {
            throw new \Exception("Object is not movable.");
        }

        $this->startMoveCommand->execute($gameObject, $velocity);
    }
}