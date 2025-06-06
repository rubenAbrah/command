<?php

namespace App\Game\Collision;

use App\Game\Interfaces\Movable;

class NeighborhoodManager
{
    private array $neighborhoods = [];
    private float $cellSize;
    private float $offsetX;
    private float $offsetY;

    public function __construct(float $cellSize, float $offsetX = 0, float $offsetY = 0)
    {
        $this->cellSize = $cellSize;
        $this->offsetX = $offsetX;
        $this->offsetY = $offsetY;
    }

    public function getNeighborhoodId(float $x, float $y): string
    {
        $gridX = floor(($x + $this->offsetX) / $this->cellSize);
        $gridY = floor(($y + $this->offsetY) / $this->cellSize);
        return "{$gridX}_{$gridY}";
    }

    public function getNeighborhood(string $id): Neighborhood
    {
        if (!isset($this->neighborhoods[$id])) {
            $this->neighborhoods[$id] = new Neighborhood($id);
        }
        return $this->neighborhoods[$id];
    }

    public function moveObject(string $objectId, float $oldX, float $oldY, float $newX, float $newY): void
    {
        $oldId = $this->getNeighborhoodId($oldX, $oldY);
        $newId = $this->getNeighborhoodId($newX, $newY);

        if ($oldId !== $newId) {
            $this->getNeighborhood($oldId)->removeObject($objectId);
            $this->getNeighborhood($newId)->addObject($objectId);
        }
    }
}