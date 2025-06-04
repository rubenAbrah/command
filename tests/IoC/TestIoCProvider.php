<?php

namespace Tests\IoC;

use App\IoC\IoC;

class TestIoCProvider
{
    public static function registerTestDependencies()
    {
        IoC::Resolve('IoC.Register', 'GameObject', function ($objectId) {
            return new class($objectId) {
                public $id;
                public function __construct($id) {
                    $this->id = $id;
                }
            };
        }, true);

        IoC::Resolve('IoC.Register', 'SetProperty', function ($object, $property, $value) {
            $object->$property = $value;
        });

        IoC::Resolve('IoC.Register', 'OperationCommand', function ($operationId, $gameObject) {
            return new class extends \App\Commands\Command {
                public function execute() {}
            };
        });

        IoC::Resolve('IoC.Register', 'GameCommandQueue', function ($gameId) {
            return new \App\ThreadSafe\ThreadSafeCommandQueue();
        }, true);
    }
}