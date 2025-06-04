<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyGameToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json(['error' => 'Token required'], 401);
        }
        try {
            $authService = app(AuthService::class);
            $decoded = $authService->validateToken($token);
            
            $request->merge([
                'game_id' => $decoded['game_id'],
                'user_id' => $decoded['sub'],
            ]);
            
            return $next($request);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
