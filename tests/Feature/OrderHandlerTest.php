<?php

namespace Tests\Feature;

use App\GameObjects\Ship;
use App\Handlers\OrderHandler;
use Tests\TestCase;

class OrderHandlerTest extends TestCase
{
    public function test_start_move_command_moves_ship()
    {
        $orderHandler = resolve(OrderHandler::class);

        $ship = new Ship('548', 'player_1');
        $orderHandler->registerObject($ship);

        $order = [
            'id' => '548',
            'action' => 'StartMove',
            'initialVelocity' => 2,
        ];

        $orderHandler->handleOrder($order, 'player_1');

        $this->assertEquals([1, 0], $ship->getPosition());
        $this->assertEquals([2, 0], $ship->getVelocity());
    }

    public function test_cannot_order_enemy_ship()
    {
        $orderHandler = resolve(OrderHandler::class);

        $ship = new Ship('548', 'enemy_player');
        $orderHandler->registerObject($ship);

        $order = [
            'id' => '548',
            'action' => 'StartMove',
            'initialVelocity' => 2,
        ];

        $this->expectException(\Exception::class);
        $orderHandler->handleOrder($order, 'my_player');
    }
}