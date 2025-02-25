<?php

namespace App\Commands;

class RetryCommand extends Command
{
    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function execute()
    {
        $this->command->execute();
    }
}