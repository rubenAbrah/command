<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\VerifyGameToken;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Tests\TestCase;

class VerifyGameTokenTest extends TestCase
{
    public function test_valid_token()
    {
        $authService = app(AuthService::class);
        $token = $authService->generateToken('user1', 'game1');
        
        $request = Request::create('/test', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . $token);
        
        $middleware = new VerifyGameToken();
        $response = $middleware->handle($request, function ($req) {
            return response()->json(['success' => true]);
        });
        
        $this->assertEquals('game1', $request->game_id);
        $this->assertEquals('user1', $request->user_id);
        $this->assertTrue($response->original['success']);
    }

    public function test_missing_token()
    {
        $request = Request::create('/test', 'GET');
        
        $middleware = new VerifyGameToken();
        $response = $middleware->handle($request, function () {});
        
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals('Token required', $response->original['error']);
    }

    public function test_invalid_token()
    {
        $request = Request::create('/test', 'GET');
        $request->headers->set('Authorization', 'Bearer invalid.token.here');
        
        $middleware = new VerifyGameToken();
        $response = $middleware->handle($request, function () {});
        
        $this->assertEquals(401, $response->getStatusCode());
    }
}