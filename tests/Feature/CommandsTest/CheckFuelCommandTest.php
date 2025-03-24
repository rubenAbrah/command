<?php

namespace Tests\Feature\CommandsTest;

use App\Commands\CheckFuelCommand;
use App\Commands\CommandException;
use PHPUnit\Framework\TestCase;

class CheckFuelCommandTest extends TestCase
{
    public function testExecuteThrowsExceptionWhenNotEnoughFuel()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage("Not enough fuel to perform the action.");

        $fuelLevel = 5; // Недостаточно топлива
        $fuelConsumption = 10; // Требуется 10 единиц топлива
        $command = new CheckFuelCommand($fuelLevel, $fuelConsumption);

        $command->execute();
    }

    public function testExecuteDoesNotThrowExceptionWhenEnoughFuel()
    {
        $fuelLevel = 15; // Достаточно топлива
        $fuelConsumption = 10; // Требуется 10 единиц топлива
        $command = new CheckFuelCommand($fuelLevel, $fuelConsumption);

        // Если исключение не выбрасывается, тест проходит успешно
        $this->assertNull($command->execute());
    }
}