<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\App\Game\Interfaces\Collidable::class, function () {
            return new \App\Game\Collision\CollisionDetector(
                config('game.collision_radius')
            );
        });
        
        $this->app->singleton(\App\Game\ObjectRegistry::class, \App\Game\ObjectRegistry::class);
        
        $this->app->singleton(\App\Game\Collision\NeighborhoodManager::class, function () {
            return new \App\Game\Collision\NeighborhoodManager(
                config('game.neighborhood_cell_size')
            );
        });
    }
}