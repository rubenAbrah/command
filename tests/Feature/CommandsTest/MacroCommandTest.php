<?php

namespace Tests\Feature\CommandsTest;

use App\Commands\MacroCommand;
use App\Commands\Command;
use App\Commands\CommandException;
use PHPUnit\Framework\TestCase;

class MacroCommandTest extends TestCase
{
    public function testExecuteRunsAllCommandsSuccessfully()
    {
        // Создаем mock-команды, которые не выбрасывают исключений
        $command1 = $this->createMock(Command::class);
        $command1->expects($this->once())->method('execute');

        $command2 = $this->createMock(Command::class);
        $command2->expects($this->once())->method('execute');

        $macroCommand = new MacroCommand([$command1, $command2]);
        $macroCommand->execute();

        // Если все команды выполнены, тест проходит успешно
        $this->assertTrue(true);
    }

    public function testExecuteStopsOnException()
    {
        // Создаем mock-команду, которая выбрасывает исключение
        $failingCommand = $this->createMock(Command::class);
        $failingCommand->method('execute')->willThrowException(new CommandException("Command failed"));

        // Создаем mock-команду, которая не должна быть выполнена
        $command2 = $this->createMock(Command::class);
        $command2->expects($this->never())->method('execute');

        $macroCommand = new MacroCommand([$failingCommand, $command2]);

        $this->expectException(CommandException::class);
        $macroCommand->execute();
    }
}