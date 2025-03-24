<?php

namespace App\Commands;

use Exception;

class MacroCommand extends Command
{
    protected $commands;

    public function __construct(array $commands)
    {
        $this->commands = $commands;
    }

    public function execute()
    {
        foreach ($this->commands as $command) {
            $command->execute();
        }
    }
}