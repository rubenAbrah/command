<?php

namespace App\GameObjects;


use App\Game\Interfaces\Movable;
use App\Game\Interfaces\GameObject;

class Ship implements GameObject, Movable
{
    private string $id;
    private string $playerId;
    private array $position = [0, 0];
    private array $velocity = [0, 0];

    public function __construct(string $id, string $playerId)
    {
        $this->id = $id;
        $this->playerId = $playerId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlayerId(): string
    {
        return $this->playerId;
    }

    public function getPosition(): ?array
    {
        return $this->position;
    }

    public function getVelocity(): ?array
    {
        return $this->velocity;
    }

    public function setPosition(array $position): void
    {
        $this->position = $position;
    }
    public function setVelocity(array $velocity): void
    {
        $this->velocity = $velocity;
    }
}