<?php

namespace App\Commands;

use App\Game\ObjectRegistry;
use App\Game\Interfaces\Movable;
use App\Game\Interfaces\Collidable;
use App\Game\Collision\NeighborhoodManager;

class CheckCollisionsWithTwoGridsCommand extends Command
{
    private Movable $object;
    private Collidable $collisionService;
    private ObjectRegistry $objectRegistry;
    private float $cellSize;

    public function __construct(
        Movable $object,
        Collidable $collisionService,
        ObjectRegistry $objectRegistry,
        float $cellSize
    ) {
        $this->object = $object;
        $this->collisionService = $collisionService;
        $this->objectRegistry = $objectRegistry;
        $this->cellSize = $cellSize;
    }

    public function execute()
    {
        $position = $this->object->getPosition();
        $x = $position[0];
        $y = $position[1];

        // Первая система окрестностей
        $manager1 = new NeighborhoodManager($this->cellSize);
        $command1 = new CheckCollisionsCommand(
            $manager1,
            $this->object,
            $this->collisionService,
            $this->objectRegistry
        );
        
        // Вторая система окрестностей со смещением
        $manager2 = new NeighborhoodManager($this->cellSize, $this->cellSize/2, $this->cellSize/2);
        $command2 = new CheckCollisionsCommand(
            $manager2,
            $this->object,
            $this->collisionService,
            $this->objectRegistry
        );

        $command1->execute();
        $command2->execute();
    }
}