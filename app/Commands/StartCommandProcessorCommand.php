<?php

namespace App\Commands;

use App\Jobs\ProcessCommandJob;
use Illuminate\Contracts\Bus\Dispatcher;

class StartCommandProcessorCommand extends Command
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function execute()
    { 
        config(['queue.default' => 'sync']);
    }
}