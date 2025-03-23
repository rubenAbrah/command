<?php

namespace App\Commands;

use App\Commands\CommandException;

class CheckFuelCommand extends Command
{
    protected $fuelLevel;
    protected $fuelConsumption;

    public function __construct(int $fuelLevel, int $fuelConsumption)
    {
        $this->fuelLevel = $fuelLevel;
        $this->fuelConsumption = $fuelConsumption;
    }

    public function execute()
    {
        if ($this->fuelLevel < $this->fuelConsumption) {
            throw new CommandException("Not enough fuel to perform the action.");
        }
    }
}