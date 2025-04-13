<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Game\Interfaces\Movable;
use App\Services\AdapterGenerator;
use Mockery;

class AdapterGeneratorTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_generates_movable_adapter()
    {
        $generator = app(AdapterGenerator::class);
        $adapterClass = $generator->generate(Movable::class);
        
        $this->assertTrue(class_exists($adapterClass));
        
        $mockObj = Mockery::mock();
        $adapter = new $adapterClass($mockObj);
        
        $this->assertInstanceOf(Movable::class, $adapter);
    }

    public function test_adapter_methods()
    {
        $generator = app(AdapterGenerator::class);
        $adapterClass = $generator->generate(Movable::class);
        
        $mockObj = Mockery::mock();
        $adapter = new $adapterClass($mockObj);
        
        // Mock IoC container
        $iocMock = Mockery::mock();
        $this->app->instance('ioc', $iocMock);
        
        // Test getPosition
        $iocMock->shouldReceive('resolve')
            ->with(Movable::class . ':getPosition', $mockObj)
            ->andReturn([10, 20]);
        
        $this->assertEquals([10, 20], $adapter->getPosition());
        
        // Test setPosition
        $commandMock = Mockery::mock();
        $commandMock->shouldReceive('execute');
        
        $iocMock->shouldReceive('resolve')
            ->with(Movable::class . ':setPosition', $mockObj, [30, 40])
            ->andReturn($commandMock);
        
        $adapter->setPosition([30, 40]);
    }
}