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
     * Fallback recommendations when AI service fails - uses real-time data
     */
    private function getFallbackRecommendations(string $query): array
    {
        try {
            // Try to use a free AI API as fallback for real-time data
            $fallbackPrompt = "Analyze the user's specific medical emergency query: '{$query}' and provide targeted immediate first aid recommendations.
            
            IMPORTANT: Focus specifically on what the user described. If they mention 'chest pain', address chest pain. If they mention 'bleeding', focus on bleeding.
            
            Format as JSON with: condition (matching user's specific query), severity (critical/urgent/moderate), summary (related to user's symptoms), immediateActions (array of specific actions), callEmergency (boolean), emergencySigns (array of specific signs).
            Include disclaimer that this is not medical advice and emergency services should be called for serious conditions.
            
            Rules:
            1. Address the specific symptoms/condition the user mentioned
            2. Provide relevant, actionable steps for their situation
            3. Be concise and focused on user's specific needs";
            
            $response = Http::timeout(10)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are an emergency medical assistant providing real-time recommendations based on current medical knowledge.'
                            ],
                            [
                                'role' => 'user',
                                'content' => $fallbackPrompt
                            ]
                        ],
                        'max_tokens' => 500,
                        'temperature' => 0.3
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? '';
                return $this->parseAIResponse($text, $query);
            }
        } catch (\Exception $e) {
            Log::error('Fallback AI service error: ' . $e->getMessage());
        }

        // Final fallback - provides specific recommendations based on query
        $queryLower = strtolower($query);
        
        if (strpos($queryLower, 'chest') !== false || strpos($queryLower, 'heart') !== false) {
            $condition = 'Chest Pain / Possible Heart Attack';
            $severity = 'critical';
            $summary = 'Based on your symptoms of chest pain, immediate medical attention may be required.';
            $actions = [
                'Call emergency services immediately (912)',
                'Have person sit down and rest',
                'Give aspirin if available and not allergic',
                'Monitor breathing and consciousness'
            ];
            $signs = ['Chest pressure or tightness', 'Pain radiating to arm/jaw', 'Shortness of breath', 'Cold sweat'];
            $callEmergency = true;
        } elseif (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false) {
            $condition = 'Severe Bleeding';
            $severity = 'critical';
            $summary = 'Based on your symptoms of bleeding, immediate action is required to stop blood loss.';
            $actions = [
                'Apply direct pressure with clean cloth',
                'Elevate injured area if possible',
                'Apply tourniquet if severe bleeding',
                'Call emergency services (912)'
            ];
            $signs = ['Heavy bleeding', 'Weakness or dizziness', 'Pale skin', 'Rapid heartbeat'];
            $callEmergency = true;
        } elseif (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'choking') !== false || strpos($queryLower, 'chocking') !== false || strpos($queryLower, 'breath') !== false) {
            $condition = (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'choking') !== false || strpos($queryLower, 'chocking') !== false) ? 'Choking / Airway Obstruction' : 'Difficulty Breathing';
            $severity = 'critical';
            $summary = 'Based on your symptoms, immediate intervention may be required for breathing.';
            $actions = [
                'Call emergency services immediately (912)',
                'Help person sit upright',
                'Perform Heimlich maneuver if choking',
                'Monitor breathing continuously'
            ];
            $signs = ['Cannot speak or breathe', 'Blue lips', 'Hands to throat', 'No coughing'];
            $callEmergency = true;
        } elseif (strpos($queryLower, 'burn') !== false) {
            $condition = 'Burns';
            $severity = 'urgent';
            $summary = 'Based on your symptoms of burns, immediate first aid is needed.';
            $actions = [
                'Cool burn with cool running water',
                'Remove jewelry or tight clothing',
                'Cover burn with sterile dressing',
                'Seek medical attention for severe burns'
            ];
            $signs = ['Large burn area', 'Deep burns', 'Burns on face/hands/genitals'];
            $callEmergency = false;
        } elseif (strpos($queryLower, 'head') !== false || strpos($queryLower, 'fall') !== false) {
            $condition = 'Head Injury';
            $severity = 'urgent';
            $summary = 'Based on your symptoms of head injury, careful assessment and medical evaluation needed.';
            $actions = [
                'Apply ice to reduce swelling',
                'Monitor for consciousness changes',
                'Avoid moving person unnecessarily',
                'Seek medical evaluation'
            ];
            $signs = ['Headache', 'Dizziness', 'Nausea', 'Vision changes', 'Confusion'];
            $callEmergency = false;
        } else {
            $condition = 'Medical Assessment Needed';
            $severity = 'moderate';
            $summary = "Based on your symptoms: '{$query}', professional medical assessment is recommended.";
            $actions = [
                'Stay calm and assess the situation',
                'Call emergency services (912) if life-threatening',
                'Provide basic first aid if trained',
                'Monitor symptoms closely'
            ];
            $signs = ['Any concerning symptoms that worry you'];
            $callEmergency = false;
        }
        
        return [
            'success' => true,
            'query' => $query,
            'aiPowered' => false,
            'recommendations' => [
                [
                    'condition' => $condition,
                    'severity' => $severity,
                    'summary' => $summary,
                    'immediateActions' => $actions,
                    'callEmergency' => $callEmergency,
                    'emergencySigns' => $signs
                ]
            ],
            'disclaimer' => 'This is not medical advice. Always consult with healthcare professionals for medical concerns. Call emergency services for life-threatening conditions.',
            'emergencyNumber' => '912'
        ];
    }
}
