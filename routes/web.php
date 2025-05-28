<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    return view('chat');
});

Route::post('/chat', function (Illuminate\Http\Request $request) {
    try {
        $request->validate(['message' => 'required|string']);
        
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            throw new \Exception('Gemini API key not configured');
        }
        
        $model = 'gemini-pro';
        $url = "https://generativelanguage.googleapis.com/v1/models/$model:generateContent?key=$apiKey";
        
        $history = $request->session()->get('chat_history', []);
        
        $contents = array_map(function($msg) {
            return ['role' => $msg['role'], 'parts' => [['text' => $msg['text']]]];
        }, $history);
        
        $contents[] = ['role' => 'user', 'parts' => [['text' => $request->message]]];
        
        $proxyUrl = 'https://cors-anywhere.herokuapp.com/';
        $response = Http::post($proxyUrl . $url, [
            'contents' => $contents,
            'generationConfig' => [
            'maxOutputTokens' => 2048,
            'temperature' => 0.9,
        ]
    ]);
        
        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            throw new \Exception('API request failed with status: '.$response->status());
        }
        
        $responseData = $response->json();
        
        if (empty($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception('No response from Gemini API');
        }
        
        $responseText = $responseData['candidates'][0]['content']['parts'][0]['text'];
        
        $newHistory = $history;
        $newHistory[] = ['role' => 'user', 'text' => $request->message];
        $newHistory[] = ['role' => 'model', 'text' => $responseText];
        
        $request->session()->put('chat_history', $newHistory);

        return response()->json([
            'response' => $responseText
        ]);
        
    } catch (\Exception $e) {
        Log::error('Chat error: '.$e->getMessage());
        return response()->json(['response' => 'Error: '.$e->getMessage()], 500);
    }
});