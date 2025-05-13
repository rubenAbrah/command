<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Commands\Command;
use Exception;
use Illuminate\Support\Facades\Log;

class ProcessCommandJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function handle()
    {
        try {
            $this->command->execute();
        } catch (Exception $e) {
            Log::error('Command execution failed: ' . get_class($this->command), [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}