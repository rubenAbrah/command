<?php

namespace App\Commands;

use Illuminate\Support\Facades\Artisan;

class SoftStopCommandProcessorCommand extends Command
{
    public function execute()
    {
        Artisan::call('queue:restart');
    }
}