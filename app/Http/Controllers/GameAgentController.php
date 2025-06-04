<?php

namespace App\Http\Controllers;

use App\DTO\AgentMessageDTO;
use App\Commands\InterpretCommand;
use App\ThreadSafe\ThreadSafeCommandQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameAgentController extends Controller
{
    public function handleMessage(Request $request, ThreadSafeCommandQueue $queue)
    {
        $data = Validator::make($request->all(), [
            'gameId' => 'required|string',
            'objectId' => 'required|string',
            'operationId' => 'required|string',
            'args' => 'nullable|array',
        ])->validate();


        $message = new AgentMessageDTO(
            $request->input('gameId'),
            $request->input('objectId'),
            $request->input('operationId'),
            $request->input('args', [])
        );

        $queue->add(new InterpretCommand($message, "game_{$message->gameId}"));

        return response()->json(['status' => 'message queued']);
    }
}