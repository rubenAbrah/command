<?php

namespace App\CommandProcessing;

use App\Commands\Command;

interface StateInterface
{
    public function handle(Command $command): ?StateInterface;
    public function process(Command $command): void;
}