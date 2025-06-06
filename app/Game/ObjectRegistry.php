<?php

namespace App\Game;

use App\Game\Interfaces\Movable;

class ObjectRegistry
{
    private array $objects = [];

    public function register(Movable $object): void
    {
        $this->objects[$object->getId()] = $object;
    }

    public function unregister(string $objectId): void
    {
        unset($this->objects[$objectId]);
    }

    public function getObject(string $objectId): ?Movable
    {
        return $this->objects[$objectId] ?? null;
    }
}