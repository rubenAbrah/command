<?php

namespace App\Providers;

use App\Game\Interfaces\Movable;
use App\Services\AdapterGenerator;
use Illuminate\Support\ServiceProvider;


class AdapterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AdapterGenerator::class, function ($app) {
            return new AdapterGenerator();
        });

        // Регистрация адаптера для Movable
        $this->app->bind(Movable::class, function ($app) {
            return $app->make(AdapterGenerator::class)->generate(Movable::class);
        });
    }
}