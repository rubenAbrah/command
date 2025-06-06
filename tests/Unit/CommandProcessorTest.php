<?php

namespace Tests\Feature;

use App\Commands\HardStopCommand;
use App\Commands\MoveToCommand;
use App\Commands\RunCommand;
use App\Services\CommandProcessor;
use App\ThreadSafe\ThreadSafeCommandQueue;
use Tests\TestCase;

class CommandProcessorTest extends TestCase
{
    public function test_hard_stop_terminates_processing()
    {
        $queue = new ThreadSafeCommandQueue();
        $processor = new CommandProcessor($queue, 10); 
        
        $queue->add(new HardStopCommand());
        $processor->start();
        
        $this->assertFalse($processor->isRunning());
    }

    public function test_move_to_command_changes_state()
    {
        $queue = new ThreadSafeCommandQueue();
        $targetQueue = new ThreadSafeCommandQueue();
        $processor = new CommandProcessor($queue, 10);
        
        $queue->add(new MoveToCommand($targetQueue));
        $queue->softStop(); 
        
        $processor->start();
        
        $this->assertEquals(
            'App\CommandProcessing\MoveToState',
            $processor->getCurrentState()
        );
    }

    public function test_run_command_returns_to_normal_state()
    {
        $queue = new ThreadSafeCommandQueue();
        $targetQueue = new ThreadSafeCommandQueue();
        $processor = new CommandProcessor($queue, 10);
        
        $queue->add(new MoveToCommand($targetQueue));
        $queue->add(new RunCommand());
        $queue->softStop();
        
        $processor->start();
        
        $this->assertEquals(
            'App\CommandProcessing\NormalState',
            $processor->getCurrentState()
        );
    }

    public function test_processor_stops_when_queue_empty_and_soft_stop()
    {
        $queue = new ThreadSafeCommandQueue();
        $processor = new CommandProcessor($queue, 10);
        
        $queue->softStop();
        
        $startTime = microtime(true);
        $processor->start();
        $duration = microtime(true) - $startTime;
        
        $this->assertFalse($processor->isRunning());
        $this->assertLessThan(0.5, $duration); 
    }
}