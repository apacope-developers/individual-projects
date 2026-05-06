<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FreeAIService
{
    protected $groqApiKey;
    protected $availableProviders;

    public function __construct()
    {
        $this->initializeProviders();
    }

    /**
     * Initialize free AI providers
     */
    private function initializeProviders()
    {
        $this->availableProviders = [];

        // Initialize Groq (Free API)
        $this->groqApiKey = config('services.groq.api_key');
        if ($this->groqApiKey) {
            $this->availableProviders[] = 'groq';
            Log::info('Groq provider initialized');
        } else {
            // Try to use Groq without API key (some free endpoints)
            Log::info('Groq API key not configured, using fallback');
        }

        if (empty($this->availableProviders)) {
            Log::warning('No free AI providers available - using fallback only');
        }
    }

    /**
     * Get AI-powered first aid recommendations using free providers
     */
    public function getFirstAidRecommendation(string $symptoms): array
    {
        if (empty($this->availableProviders)) {
            Log::warning('No free AI providers available - using fallback');
            return $this->getFallbackRecommendation($symptoms);
        }

        // Try Groq first
        if (in_array('groq', $this->availableProviders)) {
            try {
                $result = $this->getGroqRecommendation($symptoms);
                if ($result) {
                    Log::info("Got recommendation from Groq provider");
                    return $result;
                }
            } catch (\Exception $e) {
                Log::error("Failed to get recommendation from Groq: " . $e->getMessage());
            }
        }

        // All providers failed, use fallback
        Log::warning('All free AI providers failed - using fallback');
        return $this->getFallbackRecommendation($symptoms);
    }

    /**
     * Get emergency suggestions from free providers
     */
    public function getEmergencySuggestions(string $query): array
    {
        if (empty($this->availableProviders)) {
            Log::warning('No free AI providers available for suggestions - using fallback');
            return $this->getFallbackSuggestions($query);
        }

        // Try Groq first
        if (in_array('groq', $this->availableProviders)) {
            try {
                $result = $this->getGroqSuggestions($query);
                if ($result) {
                    Log::info("Got suggestions from Groq provider");
                    return $result;
                }
            } catch (\Exception $e) {
                Log::error("Failed to get suggestions from Groq: " . $e->getMessage());
            }
        }

        // All providers failed, use fallback
        Log::warning('All free AI providers failed for suggestions - using fallback');
        return $this->getFallbackSuggestions($query);
    }

    /**
     * Get recommendation from Groq
     */
    private function getGroqRecommendation(string $symptoms): array
    {
        $prompt = $this->buildFirstAidPrompt($symptoms);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->groqApiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a professional first aid and emergency medical assistant. Provide accurate, concise first aid guidance.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 500,
            'temperature' => 0.3
        ]);

        if (!$response->successful()) {
            throw new \Exception('Groq API request failed: ' . $response->body());
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? '';
        
        return $this->parseFirstAidResponse($text);
    }

    /**
     * Get suggestions from Groq
     */
    private function getGroqSuggestions(string $query): array
    {
        $prompt = "Based on this emergency query: '{$query}', suggest 3-5 possible emergency conditions that might match. Return only condition names, one per line, without numbering.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->groqApiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => 'llama-3.1-8b-instant',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a medical assistant. Provide concise emergency condition suggestions.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 100,
            'temperature' => 0.3
        ]);

        if (!$response->successful()) {
            throw new \Exception('Groq API request failed: ' . $response->body());
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? '';
        $suggestions = array_filter(array_map('trim', explode("\n", $text)));
        
        return array_slice($suggestions, 0, 5);
    }

    /**
     * Build prompt for first aid recommendations
     */
    private function buildFirstAidPrompt(string $symptoms): string
    {
        return "You are a professional first aid and emergency medical assistant. Based on the following symptoms, provide detailed first aid guidance.

Symptoms: {$symptoms}

Please provide a structured response with the following format:
1. EMERGENCY_LEVEL: (critical/urgent/moderate/minor)
2. CONDITION_NAME: (brief name of likely condition)
3. IMMEDIATE_ACTION: (single most important immediate action)
4. STEPS: (numbered list of specific first aid steps, maximum 8 steps)
5. EMERGENCY_CALL: (true/false - whether to call emergency services immediately)
6. WARNING_SIGNS: (specific symptoms that indicate worsening condition)
7. IMPORTANT_NOTES: (any critical warnings or additional information)

Keep responses concise, medically accurate, and focused on immediate first aid actions. Do not provide definitive medical diagnoses - this is first aid guidance only.";
    }

    /**
     * Parse AI response into structured data
     */
    private function parseFirstAidResponse(string $response): array
    {
        $lines = explode("\n", $response);
        $result = [
            'emergency_level' => 'moderate',
            'condition_name' => 'Unknown Condition',
            'immediate_action' => 'Monitor person',
            'steps' => [],
            'emergency_call' => false,
            'warning_signs' => [],
            'important_notes' => []
        ];

        foreach ($lines as $line) {
            $line = trim($line);
            
            if (preg_match('/1\.\s*EMERGENCY_LEVEL:\s*(.+)/i', $line, $matches)) {
                $result['emergency_level'] = strtolower(trim($matches[1]));
            } elseif (preg_match('/2\.\s*CONDITION_NAME:\s*(.+)/i', $line, $matches)) {
                $result['condition_name'] = trim($matches[1]);
            } elseif (preg_match('/3\.\s*IMMEDIATE_ACTION:\s*(.+)/i', $line, $matches)) {
                $result['immediate_action'] = trim($matches[1]);
            } elseif (preg_match('/4\.\s*STEPS:/i', $line)) {
                // Steps will be collected in following lines
            } elseif (preg_match('/5\.\s*EMERGENCY_CALL:\s*(.+)/i', $line, $matches)) {
                $value = trim($matches[1]);
                $result['emergency_call'] = strtolower($value) === 'true';
            } elseif (preg_match('/6\.\s*WARNING_SIGNS:/i', $line)) {
                // Warning signs will be collected in following lines
            } elseif (preg_match('/7\.\s*IMPORTANT_NOTES:/i', $line)) {
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
     * Fallback recommendations when AI is unavailable - uses internet APIs
     */
    private function getFallbackRecommendation(string $symptoms): array
    {
        try {
            // Try using OpenAI API as fallback
            $openaiKey = env('OPENAI_API_KEY');
            if ($openaiKey) {
                return $this->tryOpenAIRecommendation($symptoms, $openaiKey);
            }
            
            // Try using Gemini API as fallback
            $geminiKey = env('GEMINI_API_KEY');
            if ($geminiKey) {
                return $this->tryGeminiRecommendation($symptoms, $geminiKey);
            }
            
        } catch (\Exception $e) {
            Log::error('All AI fallback services failed: ' . $e->getMessage());
        }

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

    /**
     * Try OpenAI API for recommendations
     */
    private function tryOpenAIRecommendation(string $symptoms, string $apiKey): array
    {
        $prompt = $this->buildFirstAidPrompt($symptoms);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json'
        ])->post('https://api.openai.com/v1/chat/completions', [
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
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';
            return $this->parseFirstAidResponse($text);
        }
        
        throw new \Exception('OpenAI fallback failed');
    }

    /**
     * Try Gemini API for recommendations
     */
    private function tryGeminiRecommendation(string $symptoms, string $apiKey): array
    {
        $prompt = $this->buildFirstAidPrompt($symptoms);
        
        $response = Http::timeout(15)
            ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 600
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            return $this->parseFirstAidResponse($text);
        }
        
        throw new \Exception('Gemini fallback failed');
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
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $openaiKey,
                    'Content-Type' => 'application/json'
                ])->post('https://api.openai.com/v1/chat/completions', [
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
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['choices'][0]['message']['content'] ?? '';
                    $suggestions = array_filter(array_map('trim', explode("\n", $text)));
                    return array_slice($suggestions, 0, 5);
                }
            }
            
            // Try Gemini API for suggestions
            $geminiKey = env('GEMINI_API_KEY');
            if ($geminiKey) {
                $response = Http::timeout(15)
                    ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$geminiKey}", [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => "Based on this emergency query: '{$query}', suggest 3-5 possible emergency conditions. Return only condition names, one per line, no numbering."
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.3,
                            'maxOutputTokens' => 200
                        ]
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
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

    /**
     * Get available providers information
     */
    public function getAvailableProviders(): array
    {
        return $this->availableProviders;
    }
}
