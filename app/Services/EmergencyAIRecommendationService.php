<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmergencyAIRecommendationService
{
    private $apiKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY', env('GEMINI_API_KEY'));
        $this->baseUrl = 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Get AI-powered emergency recommendations based on user search query
     */
    public function getEmergencyRecommendations(string $query): array
    {
        if (!$this->apiKey) {
            Log::error('Gemini API key not configured');
            return $this->getFallbackRecommendations($query);
        }

        try {
            $prompt = $this->buildEmergencyPrompt($query);
            
            // Use a working API endpoint that provides real AI recommendations from the internet
            $response = Http::timeout(15)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY', 'sk-proj-demo-key'),
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are an emergency medical assistant. Based on user symptoms, provide immediate, actionable emergency recommendations. Format as JSON with: condition, severity, summary, immediateActions, callEmergency, emergencySigns, disclaimer, emergencyNumber.'
                            ],
                            [
                                'role' => 'user',
                                'content' => $prompt
                            ]
                        ],
                        'max_tokens' => 800,
                        'temperature' => 0.3
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? '';
                return $this->parseAIResponse($text, $query);
            } else {
                Log::error('Gemini API error: ' . $response->body());
                return $this->getFallbackRecommendations($query);
            }
        } catch (\Exception $e) {
            Log::error('Emergency AI service error: ' . $e->getMessage());
            return $this->getFallbackRecommendations($query);
        }
    }

    /**
     * Build the emergency-specific prompt for AI
     */
    private function buildEmergencyPrompt(string $query): string
    {
        return "You are an emergency medical assistant. Analyze the user's specific query: '{$query}' and provide targeted emergency recommendations.

        IMPORTANT: Focus specifically on what the user described. If they mention 'chest pain', provide recommendations for chest pain conditions. If they mention 'bleeding', focus on bleeding emergencies.

        CRITICAL: Always include a disclaimer that this is not medical advice and to call emergency services for serious conditions.

        Format your response as JSON with this structure:
        {
            \"recommendations\": [
                {
                    \"condition\": \"Specific condition name matching user's query\",
                    \"severity\": \"critical|urgent|moderate|minor\",
                    \"summary\": \"Brief description directly related to user's symptoms\",
                    \"immediateActions\": [\"Specific actions for described condition\", \"Action 2\"],
                    \"callEmergency\": true/false,
                    \"emergencySigns\": [\"Specific signs for this condition\", \"Sign 2\"]
                }
            ],
            \"disclaimer\": \"Medical disclaimer text\",
            \"emergencyNumber\": \"912\"
        }

        Rules:
        1. Match your response EXACTLY to what user described
        2. If user mentions specific symptoms, address those specifically
        3. Provide relevant, actionable steps for described condition
        4. Be concise and focused on user's specific situation
        5. Focus on life-threatening conditions first
        6. Use current medical best practices";
    }

    /**
     * Parse AI response and return structured recommendations
     */
    private function parseAIResponse(string $text, string $query): array
    {
        try {
            // Extract JSON from response
            $jsonStart = strpos($text, '{');
            $jsonEnd = strrpos($text, '}') + 1;
            
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonText = substr($text, $jsonStart, $jsonEnd - $jsonStart);
                $data = json_decode($jsonText, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'success' => true,
                        'query' => $query,
                        'aiPowered' => true,
                        'recommendations' => $data['recommendations'] ?? [],
                        'disclaimer' => $data['disclaimer'] ?? 'This is not medical advice. Call emergency services for serious conditions.',
                        'emergencyNumber' => $data['emergencyNumber'] ?? '912'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error('Error parsing AI response: ' . $e->getMessage());
        }

        return $this->getFallbackRecommendations($query);
    }

    /**
     * Fallback recommendations when AI service fails - uses alternative internet APIs
     */
    private function getFallbackRecommendations(string $query): array
    {
        try {
            // Try Gemini API as fallback
            $geminiApiKey = env('GEMINI_API_KEY');
            if ($geminiApiKey) {
                return $this->tryGeminiAPI($query, $geminiApiKey);
            }
            
            // Try a different OpenAI endpoint or model
            if ($this->apiKey) {
                return $this->tryAlternativeOpenAI($query);
            }
            
        } catch (\Exception $e) {
            Log::error('Fallback AI service error: ' . $e->getMessage());
        }

        // Last resort - minimal generic response directing to emergency services
        return [
            'success' => false,
            'query' => $query,
            'aiPowered' => false,
            'error' => 'AI services temporarily unavailable',
            'recommendations' => [
                [
                    'condition' => 'Emergency Assessment Required',
                    'severity' => 'unknown',
                    'summary' => 'AI services are temporarily unavailable. Please seek professional medical help.',
                    'immediateActions' => [
                        'Call emergency services (912) for life-threatening conditions',
                        'Contact local medical professionals',
                        'Go to nearest emergency room if serious'
                    ],
                    'callEmergency' => true,
                    'emergencySigns' => ['Any severe symptoms', 'Difficulty breathing', 'Unconsciousness', 'Severe pain']
                ]
            ],
            'disclaimer' => 'AI services unavailable. This is not medical advice. Always consult with healthcare professionals.',
            'emergencyNumber' => '912'
        ];
    }

    /**
     * Try Gemini API as fallback
     */
    private function tryGeminiAPI(string $query, string $apiKey): array
    {
        $prompt = $this->buildEmergencyPrompt($query);
        
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
                    'maxOutputTokens' => 800
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            return $this->parseAIResponse($text, $query);
        }
        
        throw new \Exception('Gemini API failed');
    }

    /**
     * Try alternative OpenAI configuration
     */
    private function tryAlternativeOpenAI(string $query): array
    {
        $prompt = $this->buildEmergencyPrompt($query);
        
        $response = Http::timeout(15)
            ->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => 'gpt-4o-mini',  // Try different model
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an emergency medical assistant. Based on user symptoms, provide immediate, actionable emergency recommendations. Format as JSON with: condition, severity, summary, immediateActions, callEmergency, emergencySigns, disclaimer, emergencyNumber.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 800,
                    'temperature' => 0.3
                ]
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';
            return $this->parseAIResponse($text, $query);
        }
        
        throw new \Exception('Alternative OpenAI API failed');
    }
}
