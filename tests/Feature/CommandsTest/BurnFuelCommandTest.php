<?php

namespace Tests\Feature\CommandsTest;

use App\Commands\BurnFuelCommand;
use PHPUnit\Framework\TestCase;

class BurnFuelCommandTest extends TestCase
{
    public function testExecuteDecreasesFuelLevel()
    {
        $fuelLevel = 20; // Начальный уровень топлива
        $fuelConsumption = 5; // Расход топлива

        $command = new BurnFuelCommand($fuelLevel, $fuelConsumption);
        $command->execute();

        // Проверяем, что уровень топлива уменьшился
        $this->assertEquals(15, $fuelLevel);
    }

    public function testExecuteDoesNotAllowNegativeFuelLevel()
    {
        $fuelLevel = 3; // Начальный уровень топлива
        $fuelConsumption = 5; // Расход топлива

        $command = new BurnFuelCommand($fuelLevel, $fuelConsumption);
        $command->execute();

        // Проверяем, что уровень топлива не стал отрицательным
        $this->assertEquals(0, $fuelLevel);
    }
}