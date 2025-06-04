<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyGameToken;
use App\Http\Controllers\GameAgentController;

Route::prefix('auth')->group(function () {
    Route::post('/create-game', [AuthController::class, 'createGame']);
    Route::post('/token', [AuthController::class, 'getToken']);
});

Route::middleware([VerifyGameToken::class])->group(function () {
    Route::post('/game/message', [GameAgentController::class, 'handleMessage']);
});