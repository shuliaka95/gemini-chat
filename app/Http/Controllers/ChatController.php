<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class ChatController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        
        $chatResult = $this->gemini->chat(
            $request->session()->get('chat_history', []),
            $request->input('message')
        );

        $request->session()->put('chat_history', $chatResult['history']);

        return response()->json([
            'response' => $chatResult['response']
        ]);
    }

    public function index()
    {
        return view('chat');
    }
}