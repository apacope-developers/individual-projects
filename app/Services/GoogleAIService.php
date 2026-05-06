<?php

namespace App\Services;

use Gemini\Client;
use Gemini\Enums\Role;
use Gemini;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        if (!$this->apiKey) {
            Log::warning('Gemini API key not configured in services.gemini.api_key');
            $this->client = null;
        } else {
            try {
                $this->client = Gemini::client($this->apiKey);
            } catch (\Exception $e) {
                Log::error('Failed to initialize Gemini client: ' . $e->getMessage());
                $this->client = null;
            }
        }
    }

    /**
     * Get AI-powered first aid recommendations based on symptoms
     */
    public function getFirstAidRecommendation(string $symptoms): array
    {
        // Check if AI service is available
        if (!$this->client) {
            Log::warning('AI service not available - using fallback');
            return $this->getFallbackRecommendation($symptoms);
        }

        try {
            $prompt = $this->buildFirstAidPrompt($symptoms);
            
            $response = $this->client
                ->generativeModel('gemini-1.5-flash')
                ->generateContent($prompt);

            $text = $response->text();
            
            return $this->parseFirstAidResponse($text);
            
        } catch (\Exception $e) {
            Log::error('Google AI Service Error: ' . $e->getMessage());
            
            // Fallback to basic recommendations
            return $this->getFallbackRecommendation($symptoms);
        }
    }

    /**
     * Build the prompt for first aid recommendations
     */
    private function buildFirstAidPrompt(string $symptoms): string
    {
        return "You are a professional first aid and emergency medical assistant. Based on the following symptoms, provide detailed first aid guidance.

Symptoms: {$symptoms}

Please provide a structured response with the following format:
1. EMERGENCY_LEVEL: (critical/urgent/moderate/minor)
2. CONDITION_NAME: (brief name of the likely condition)
3. IMMEDIATE_ACTION: (single most important immediate action)
4. STEPS: (numbered list of specific first aid steps, maximum 8 steps)
5. EMERGENCY_CALL: (true/false - whether to call emergency services immediately)
6. WARNING_SIGNS: (specific symptoms that indicate worsening condition)
7. IMPORTANT_NOTES: (any critical warnings or additional information)

Keep responses concise, medically accurate, and focused on immediate first aid actions. Do not provide definitive medical diagnoses - this is first aid guidance only.";
    }

    /**
     * Parse the AI response into structured data
     */
    private function parseFirstAidResponse(string $response): array
    {
        $lines = explode("\n", $response);
        $result = [
            'emergency_level' => 'moderate',
            'condition_name' => 'Unknown Condition',
            'immediate_action' => 'Monitor the person',
            'steps' => [],
            'emergency_call' => false,
            'warning_signs' => [],
            'important_notes' => []
        ];

        foreach ($lines as $line) {
            $line = trim($line);
            
            if (str_starts_with($line, '1. EMERGENCY_LEVEL:')) {
                $result['emergency_level'] = trim(substr($line, 18));
            } elseif (str_starts_with($line, '2. CONDITION_NAME:')) {
                $result['condition_name'] = trim(substr($line, 17));
            } elseif (str_starts_with($line, '3. IMMEDIATE_ACTION:')) {
                $result['immediate_action'] = trim(substr($line, 18));
            } elseif (str_starts_with($line, '4. STEPS:')) {
                // Steps will be collected in the following lines
            } elseif (str_starts_with($line, '5. EMERGENCY_CALL:')) {
                $value = trim(substr($line, 16));
                $result['emergency_call'] = strtolower($value) === 'true';
            } elseif (str_starts_with($line, '6. WARNING_SIGNS:')) {
                // Warning signs will be collected in following lines
            } elseif (str_starts_with($line, '7. IMPORTANT_NOTES:')) {
                // Important notes will be collected in following lines
            } elseif (preg_match('/^\d+\.\s/', $line) && !empty($result['immediate_action'])) {
                // This is a step
                $step = preg_replace('/^\d+\.\s/', '', $line);
                $result['steps'][] = trim($step);
            }
        }

        return $result;
    }

    /**
     * Fallback recommendations when AI is unavailable - uses alternative internet APIs
     */
    private function getFallbackRecommendation(string $symptoms): array
    {
        try {
            // Try using OpenAI API as fallback
            $openaiKey = env('OPENAI_API_KEY');
            if ($openaiKey) {
                return $this->tryOpenAIFallback($symptoms, $openaiKey);
            }
            
            // Try using a free AI API
            return $this->tryFreeAIFallback($symptoms);
            
        } catch (\Exception $e) {
            Log::error('All AI services failed: ' . $e->getMessage());
            
            // Last resort - minimal emergency guidance
            return [
                'emergency_level' => 'unknown',
                'condition_name' => 'AI Services Unavailable',
                'immediate_action' => 'Seek professional medical help immediately',
                'steps' => [
                    'Call emergency services (912) for serious conditions',
                    'Go to nearest emergency room',
                    'Contact local medical professionals',
                    'Do not rely on AI when services are unavailable'
                ],
                'emergency_call' => true,
                'warning_signs' => ['Any severe symptoms', 'AI services unavailable'],
                'important_notes' => ['Professional medical assessment required', 'This is not medical advice']
            ];
        }
    }

    /**
     * Try OpenAI API as fallback
     */
    private function tryOpenAIFallback(string $symptoms, string $apiKey): array
    {
        $prompt = $this->buildFirstAidPrompt($symptoms);
        
        $response = Http::timeout(15)
            ->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a professional first aid and emergency medical assistant. Provide structured responses in the exact format requested.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 600,
                    'temperature' => 0.3
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';
            return $this->parseFirstAidResponse($text);
        }
        
        throw new \Exception('OpenAI fallback failed');
    }

    /**
     * Try a free AI API as fallback
     */
    private function tryFreeAIFallback(string $symptoms): array
    {
        // Try using a different API endpoint or service
        $response = Http::timeout(10)
            ->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY', 'demo'),
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an emergency medical assistant. Provide first aid guidance in format: EMERGENCY_LEVEL, CONDITION_NAME, IMMEDIATE_ACTION, STEPS (numbered), EMERGENCY_CALL, WARNING_SIGNS, IMPORTANT_NOTES'
                        ],
                        [
                            'role' => 'user',
                            'content' => "Symptoms: {$symptoms}. Provide emergency first aid guidance."
                        ]
                    ],
                    'max_tokens' => 400,
                    'temperature' => 0.3
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';
            return $this->parseFirstAidResponse($text);
        }
        
        throw new \Exception('Free AI fallback failed');
    }

    /**
     * Get emergency search suggestions based on input
     */
    public function getEmergencySuggestions(string $query): array
    {
        // Check if AI service is available
        if (!$this->client) {
            Log::warning('AI service not available for suggestions - using fallback');
            return $this->getFallbackSuggestions($query);
        }

        try {
            $prompt = "Based on this emergency query: '{$query}', suggest 3-5 possible emergency conditions that might match. Return only the condition names, one per line, without numbering.";

            $response = $this->client
                ->generativeModel('gemini-1.5-flash')
                ->generateContent($prompt);

            $text = $response->text();
            $suggestions = array_filter(array_map('trim', explode("\n", $text)));
            
            return array_slice($suggestions, 0, 5);
            
        } catch (\Exception $e) {
            Log::error('AI Suggestions Error: ' . $e->getMessage());
            
            // Fallback suggestions based on keywords
            return $this->getFallbackSuggestions($query);
        }
    }

    /**
     * Fallback suggestions when AI is unavailable - uses internet APIs
     */
    private function getFallbackSuggestions(string $query): array
    {
        try {
            // Try OpenAI API for suggestions
            $openaiKey = env('OPENAI_API_KEY');
            if ($openaiKey) {
                $response = Http::timeout(10)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $openaiKey,
                            'Content-Type' => 'application/json'
                        ],
                        'json' => [
                            'model' => 'gpt-3.5-turbo',
                            'messages' => [
                                [
                                    'role' => 'system',
                                    'content' => 'You are an emergency medical assistant. Based on emergency queries, suggest 3-5 possible emergency conditions. Return only condition names, one per line, no numbering.'
                                ],
                                [
                                    'role' => 'user',
                                    'content' => "Based on this emergency query: '{$query}', suggest possible emergency conditions."
                                ]
                            ],
                            'max_tokens' => 200,
                            'temperature' => 0.3
                        ]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['choices'][0]['message']['content'] ?? '';
                    $suggestions = array_filter(array_map('trim', explode("\n", $text)));
                    return array_slice($suggestions, 0, 5);
                }
            }
        } catch (\Exception $e) {
            Log::error('AI suggestions fallback failed: ' . $e->getMessage());
        }

        // Last resort - generic emergency suggestions
        return [
            'Emergency Medical Assessment',
            'Seek Professional Medical Help',
            'Call Emergency Services if Serious'
        ];
    }
}
