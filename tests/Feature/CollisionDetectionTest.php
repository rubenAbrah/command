<?php

namespace Tests\Feature;

use App\Game\Collision\CollisionDetector;
use App\Game\Interfaces\Movable;
use Tests\TestCase;

class CollisionDetectionTest extends TestCase
{
    public function test_collision_detection()
    {
        $detector = new CollisionDetector(0.5);

        $object1 = $this->createMock(Movable::class);
        $object1->method('getPosition')->willReturn([0, 0]);

        $object2 = $this->createMock(Movable::class);
        $object2->method('getPosition')->willReturn([0.5, 0.5]);

        $object3 = $this->createMock(Movable::class);
        $object3->method('getPosition')->willReturn([2, 2]);

        $this->assertTrue($detector->checkCollision($object1, $object2));

        $this->assertFalse($detector->checkCollision($object1, $object3));
    }

    public function test_collision_edge_cases()
    {
        $detector = new CollisionDetector(1.0);

        $object1 = $this->createMock(Movable::class);
        $object1->method('getPosition')->willReturn([0, 0]);

        $object2 = $this->createMock(Movable::class);
        $object2->method('getPosition')->willReturn([2, 0]);

        $object3 = $this->createMock(Movable::class);
        $object3->method('getPosition')->willReturn([1.99, 0]);

        $this->assertFalse($detector->checkCollision($object1, $object2));
        $this->assertTrue($detector->checkCollision($object1, $object3));
    }
}