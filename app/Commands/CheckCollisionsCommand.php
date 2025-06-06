<?php

namespace App\Commands;

use App\Game\ObjectRegistry;
use App\Game\Interfaces\Movable;
use App\Game\Interfaces\Collidable;
use App\Game\Collision\NeighborhoodManager;

class CheckCollisionsCommand extends Command
{
    private NeighborhoodManager $manager;
    private Movable $object;
    private Collidable $collisionService;
    private ObjectRegistry $objectRegistry;

    public function __construct(
        NeighborhoodManager $manager,
        Movable $object,
        Collidable $collisionService,
        ObjectRegistry $objectRegistry
    ) {
        $this->manager = $manager;
        $this->object = $object;
        $this->collisionService = $collisionService;
        $this->objectRegistry = $objectRegistry;
    }

    public function execute()
    {
        $position = $this->object->getPosition();
        $neighborhoodId = $this->manager->getNeighborhoodId($position[0], $position[1]);
        $neighborhood = $this->manager->getNeighborhood($neighborhoodId);

        foreach ($neighborhood->getObjects() as $otherObjectId) {
            if ($otherObjectId !== $this->object->getId()) {
                $otherObject = $this->objectRegistry->getObject($otherObjectId);
                if ($otherObject !== null) {
                    $this->collisionService->checkCollision($this->object, $otherObject);
                }
            }
        }
    }
}