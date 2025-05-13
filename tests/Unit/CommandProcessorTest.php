<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Commands\Command;
use App\Jobs\ProcessCommandJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Commands\StartCommandProcessorCommand;
use App\Commands\HardStopCommandProcessorCommand;
use App\Commands\SoftStopCommandProcessorCommand;

class CommandProcessorTest extends TestCase
{
    public function test_job_dispatched()
    {
        Queue::fake();

        $command = new class extends Command {
            public function execute()
            {
            }
        };

        $job = new ProcessCommandJob($command);
        app(Dispatcher::class)->dispatch($job);

        Queue::assertPushed(ProcessCommandJob::class);
    }

    public function test_start_command()
    {
        $startCommand = new StartCommandProcessorCommand(app(Dispatcher::class));
        $startCommand->execute();

        $this->assertEquals('sync', config('queue.default'));
    }

    public function test_hard_stop_command()
    {
        Artisan::shouldReceive('call')
            ->once()
            ->with('queue:clear', [
                '--queue' => 'default',
                '--force' => true
            ]);

        $hardStopCommand = new HardStopCommandProcessorCommand();
        $hardStopCommand->execute();

        $this->assertTrue(true);
    }

    public function test_soft_stop_command()
    {
        Artisan::shouldReceive('call')
            ->once()
            ->with('queue:restart');

        $softStopCommand = new SoftStopCommandProcessorCommand();
        $softStopCommand->execute();

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}