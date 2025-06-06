<?php

namespace App\Commands;

use App\Game\Collision\NeighborhoodManager;
use App\Game\Interfaces\Movable;

class UpdateNeighborhoodCommand extends Command
{
    private Movable $object;
    private NeighborhoodManager $manager;
    private float $oldX;
    private float $oldY;

    public function __construct(Movable $object, NeighborhoodManager $manager, float $oldX, float $oldY)
    {
        $this->object = $object;
        $this->manager = $manager;
        $this->oldX = $oldX;
        $this->oldY = $oldY;
    }

    public function execute()
    {
        $position = $this->object->getPosition();
        $this->manager->moveObject(
            $this->object->getId(),
            $this->oldX,
            $this->oldY,
            $position[0],
            $position[1]
        );
    }
}