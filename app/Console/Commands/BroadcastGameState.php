<?php

namespace App\Console\Commands;

use App\Events\GameStateUpdated;
use Illuminate\Console\Command;

class BroadcastGameState extends Command
{
    protected $signature = 'game:broadcast-state {gameId}';
    protected $description = 'Broadcast game state to connected agents';

    public function handle()
    {
        $gameId = $this->argument('gameId');
        $state = [];
        
        event(new GameStateUpdated($gameId, $state));
        $this->info("Game state broadcasted for game: {$gameId}");
    }
}