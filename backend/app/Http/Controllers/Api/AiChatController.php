<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use App\Models\Message;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use OpenAI\Laravel\Facades\OpenAI;

use App\Events\PrivateMessageSent;

class AiChatController extends Controller
{
    /*
    =========================================
    AI CHAT
    =========================================
    */

    public function ask(Request $request)
    {
        $request->validate([

            'message' =>

                'required|string',
        ]);

        /*
        AI USER
        */

        $aiUser = User::where(
            'email',
            'ai@chat.com'
        )->first();

        /*
        save user message
        */

        $userMessage = Message::create([

            'sender_id' =>

                auth()->id(),

            'receiver_id' =>

                $aiUser->id,

            'message' =>

                $request->message,

            'type' => 'text',

            'is_seen' => true,

            'seen_at' => now(),
        ]);

        /*
        OPENAI RESPONSE
        */

        $response = OpenAI::chat()->create([

            'model' => 'gpt-4.1-mini',

            'messages' => [

                [
                    'role' => 'system',

                    'content' =>
                        'You are a helpful AI assistant inside a chat app.'
                ],

                [
                    'role' => 'user',

                    'content' =>
                        $request->message
                ]
            ],
        ]);

        $aiReply = $response
            ->choices[0]
            ->message
            ->content;

        /*
        save ai reply
        */

        $aiMessage = Message::create([

            'sender_id' =>

                $aiUser->id,

            'receiver_id' =>

                auth()->id(),

            'message' =>

                $aiReply,

            'type' => 'text',

            'is_seen' => false,
        ]);

        /*
        realtime
        */

        broadcast(
            new PrivateMessageSent($aiMessage)
        )->toOthers();

        return response()->json([

            'status' => true,

            'user_message' => $userMessage,

            'ai_message' => $aiMessage,
        ]);
    }
}