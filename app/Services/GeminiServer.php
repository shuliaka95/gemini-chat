<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        $this->model = config('gemini.model', 'gemini-pro');
    }

    public function chat(array $history, string $message): array
    {
        $url = "https://generativelanguage.googleapis.com/v1/models/{$this->model}:generateContent?key={$this->apiKey}";

        // Build contents array
        $contents = array_map(function($msg) {
            return ['role' => $msg['role'], 'parts' => [['text' => $msg['text']]]];
        }, $history);

        // Add new message
        $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];

        $response = Http::post($url, [
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => 2048,
                'temperature' => 0.9,
            ]
        ]);

        $responseData = $response->json();

        // Extract response text
        $responseText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';

        // Update history
        $newHistory = $history;
        $newHistory[] = ['role' => 'user', 'text' => $message];
        $newHistory[] = ['role' => 'model', 'text' => $responseText];

        return [
            'response' => $responseText,
            'history' => $newHistory
        ];
    }
}