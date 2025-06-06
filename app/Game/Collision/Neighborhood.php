<?php

namespace App\Game\Collision;

class Neighborhood
{
    private string $id;
    private array $objects = [];

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function addObject(string $objectId): void
    {
        if (!in_array($objectId, $this->objects)) {
            $this->objects[] = $objectId;
        }
    }

    public function removeObject(string $objectId): void
    {
        $this->objects = array_diff($this->objects, [$objectId]);
    }

    public function getObjects(): array
    {
        return $this->objects;
    }
}