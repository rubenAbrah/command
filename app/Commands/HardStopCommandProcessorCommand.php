<?php

namespace App\Commands;

use Illuminate\Support\Facades\Artisan;

class HardStopCommandProcessorCommand extends Command
{
    public function execute()
    {
        Artisan::call('queue:clear', [
            '--queue' => 'default',
            '--force' => true,
        ]);
    }
}