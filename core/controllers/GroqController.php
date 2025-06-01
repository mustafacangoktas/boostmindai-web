<?php

namespace Core\Controllers;

use Core\Database;
use Core\Services\I18n;
use Exception;

/**
 * Groq API Controller
 *
 * Handles communication with Groq's OpenAI-compatible chat completions API.
 *
 * Responsibilities:
 * - Randomly selects an API key
 * - Loads system prompts from file
 * - Merges prompt with chat history and preferred language
 * - Sends a chat completion request and parses the response
 *
 * @package Core\Controllers
 * @author Mustafa Can
 */
class GroqController
{
    /**
     * Sends a chat completion request to Groq API
     *
     * @param string $promptFile Name of the file containing system prompt lines
     * @param array $history Chat history with 'role' and 'content' for each entry
     * @return string The generated response message, or an error fallback
     */
    public static function callModel(string $promptFile, array $history): string
    {
        global $config;
        // Random API key selection
        $apiKeys = $config['GROQ_API_KEYS'] ?? [];
        $apiKey = trim($apiKeys[array_rand($apiKeys)]);
        $url = 'https://api.groq.com/openai/v1/chat/completions';

        // Read system prompts from file
        $systemPrompts = file("prompts/" . $promptFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($systemPrompts === false) {
            return 'Failed to load system prompts.';
        }
        $messages = array_map(fn($line) => ['role' => 'system', 'content' => $line], $systemPrompts);

        $languageNames = [
            'en' => 'English',
            'tr' => 'Turkish',
            'de' => 'German',
            'fr' => 'French',
        ];

        // Enforce preferred language
        $preferredLang = I18n::getPreferredLanguage();
        $messages[] = ['role' => 'system', 'content' => 'Always use the ' .
            ($languageNames[$preferredLang] ?? 'English') . ' language for responses.'];

        // Merge with user chat history
        $messages = array_merge($messages, $history);

        // Prepare request body
        $body = json_encode([
            'model' => 'meta-llama/llama-4-maverick-17b-128e-instruct',
            'messages' => $messages,
            'temperature' => 0.9,
            'max_tokens' => 512,
        ]);

        // Execute HTTP request via cURL
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        return $data['choices'][0]['message']['content'];
    }
}
