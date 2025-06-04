<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;

class AuthService
{
    public function createGame(array $participants): string
    {
        $gameId = uniqid('game_', true);
        
        DB::table('game_participants')->insert([
            'game_id' => $gameId,
            'participants' => json_encode($participants),
            'created_at' => now(),
        ]);
        
        return $gameId;
    }

    public function generateToken(string $userId, string $gameId): string
    {
        $payload = [
            'iss' => config('jwt.issuer'),
            'sub' => $userId,
            'game_id' => $gameId,
            'iat' => time(),
            'exp' => time() + config('jwt.expire'),
        ];
        
        return JWT::encode($payload, config('jwt.secret'), config('jwt.algo'));
    }

    public function validateToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key(config('jwt.secret'), config('jwt.algo')));
            return (array)$decoded;
        } catch (\Exception $e) {
            throw new \RuntimeException('Invalid token: ' . $e->getMessage());
        }
    }
}