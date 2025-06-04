<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameAgentController;

Route::post('/agent/message', [GameAgentController::class, 'handleMessage']);