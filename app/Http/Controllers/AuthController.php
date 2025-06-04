<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function createGame(Request $request, AuthService $authService)
    {
        $request->validate([
            'participants' => 'required|array',
            'participants.*' => 'string',
        ]);

        $gameId = $authService->createGame($request->participants);

        return response()->json([
            'game_id' => $gameId,
        ]);
    }

    public function getToken(Request $request, AuthService $authService)
    {
        $request->validate([
            'user_id' => 'required|string',
            'game_id' => 'required|string',
        ]);

        $game = DB::table('game_participants')
            ->where('game_id', $request->game_id)
            ->first();

        if (!$game) {
            return response()->json(['error' => 'Game not found'], 404);
        }

        $participants = json_decode($game->participants, true);

        if (!in_array($request->user_id, $participants)) {
            return response()->json(['error' => 'User not in game participants'], 403);
        }

        $token = $authService->generateToken($request->user_id, $request->game_id);

        return response()->json([
            'token' => $token,
        ]);
    }
}