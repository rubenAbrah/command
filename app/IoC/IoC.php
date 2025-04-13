<?php

namespace App\IoC;

class IoC
{
    private static $container = [];
    private static $scopes = [];
    private static $currentScope = 'default';

    private static function initialize()
    {
        if (!isset(self::$scopes['default'])) {
            self::$scopes['default'] = [
                'parent' => null,
                'instances' => [],
            ];
        }
    }

    public static function Resolve(string $key, ...$args)
    {
        self::initialize();
        if ($key === 'IoC.Register') {
            return self::register($args[0], $args[1], $args[2] ?? false);
        } elseif ($key === 'Scopes.New') {
            return self::newScope($args[0], $args[1] ?? null);
        } elseif ($key === 'Scopes.Current') {
            return self::setCurrentScope($args[0]);
        } else {
            return self::resolveDependency($key, $args);
        }
    }

    private static function register(string $key, callable $factory, bool $isSingleton = false)
    {
        self::$container[$key] = [
            'factory' => $factory,
            'isSingleton' => $isSingleton,
            'instance' => null,
        ];
        return new class {
            public function Execute()
            {
            }
        };
    }

    private static function newScope(string $scopeId, ?string $parentScopeId = null)
    {
        if ($parentScopeId && !isset(self::$scopes[$parentScopeId])) {
            throw new \Exception("Parent scope not found: $parentScopeId");
        }

        self::$scopes[$scopeId] = [
            'parent' => $parentScopeId,
            'instances' => [],
        ];
        return new class {
            public function Execute()
            {
            }
        };
    }

    private static function setCurrentScope(string $scopeId)
    {
        if (!isset(self::$scopes[$scopeId])) {
            throw new \Exception("Scope not found: $scopeId");
        }
        self::$currentScope = $scopeId;
        return new class {
            public function Execute()
            {
            }
        };
    }

    private static function resolveDependency(string $key, array $args)
    {
        if (!isset(self::$container[$key])) {
            throw new \Exception("Dependency not found: $key");
        }

        $dependency = self::$container[$key];
        $scopeId = self::$currentScope;

        while ($scopeId !== null) {
            $scope = self::$scopes[$scopeId];

            if ($dependency['isSingleton'] && isset($scope['instances'][$key])) {
                return $scope['instances'][$key];
            }

            $scopeId = $scope['parent'];
        }

        if ($dependency['isSingleton']) {
            $instance = $dependency['factory'](...$args);
            self::$scopes[self::$currentScope]['instances'][$key] = $instance;
            return $instance;
        }

        return $dependency['factory'](...$args);
    }

    public static function getCurrentScope()
    {
        return self::$currentScope;
    }

    public static function getScope(string $scopeId)
    {
        return self::$scopes[$scopeId] ?? null;
    }
}