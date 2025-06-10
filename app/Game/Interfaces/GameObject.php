<?php
namespace App\Game\Interfaces;

interface GameObject
{
    public function getId(): string;
    public function getPlayerId(): string;
}