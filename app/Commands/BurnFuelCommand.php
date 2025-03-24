<?php

namespace App\Commands;

class BurnFuelCommand extends Command
{
    protected $fuelLevel;
    protected $fuelConsumption;

    public function __construct(int &$fuelLevel, int $fuelConsumption)
    {
        $this->fuelLevel = &$fuelLevel;
        $this->fuelConsumption = $fuelConsumption;
    }

    public function execute()
    {
        // Уменьшаем уровень топлива, но не ниже нуля
        $this->fuelLevel = max(0, $this->fuelLevel - $this->fuelConsumption);
    }
}