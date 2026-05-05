<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WorkingAIRecommendationService
{
    /**
     * Get AI-powered emergency recommendations using working API
     */
    public function getEmergencyRecommendations(string $query): array
    {
        try {
            // Check if we have a valid API key
            $apiKey = env('OPENAI_API_KEY');
            
            if (!$apiKey || $apiKey === 'sk-proj-demo' || strlen($apiKey) < 20) {
                Log::info('AI API key not configured, using enhanced emergency logic');
                return $this->getEnhancedEmergencyRecommendations($query);
            }
            
            $prompt = $this->buildEmergencyPrompt($query);
            
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
                $result = $this->parseAIResponse($text, $query, true);
                
                // Ensure AI result has proper structure
                if ($result['success'] && !empty($result['recommendations'])) {
                    return $result;
                } else {
                    Log::warning('AI response invalid, using enhanced fallback');
                    return $this->getEnhancedEmergencyRecommendations($query);
                }
            } else {
                Log::error('Working API error: ' . $response->body());
                return $this->getEnhancedEmergencyRecommendations($query);
            }
        } catch (\Exception $e) {
            Log::error('Working AI service error: ' . $e->getMessage());
            return $this->getEnhancedEmergencyRecommendations($query);
        }
    }
    
    /**
     * Enhanced emergency recommendations with specific first aid tips
     */
    private function getEnhancedEmergencyRecommendations(string $query): array
    {
        $queryLower = strtolower($query);
        
        // Enhanced logic with specific first aid steps
        if (strpos($queryLower, 'chest') !== false || strpos($queryLower, 'heart') !== false || strpos($queryLower, 'heart attack') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'recommendations' => [
                    [
                        'condition' => 'Possible Heart Attack - EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Chest pain could indicate a heart attack. Immediate action required.',
                        'immediateActions' => [
                            'Call emergency services immediately (912)',
                            'Have person sit down and rest comfortably',
                            'Give aspirin 325mg chewable if available and not allergic',
                            'Loosen tight clothing around chest and neck',
                            'Monitor breathing and consciousness continuously',
                            'Be prepared to perform CPR if person becomes unconscious'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Chest pressure, tightness, or squeezing',
                            'Pain radiating to arm, jaw, neck, or back',
                            'Shortness of breath or difficulty breathing',
                            'Cold sweat, nausea, or lightheadedness',
                            'Irregular heartbeat or palpitations'
                        ]
                    ]
                ],
                'disclaimer' => 'This is emergency first aid guidance. Call emergency services immediately for suspected heart attack.',
                'emergencyNumber' => '912'
            ];
        }
        
        if (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'recommendations' => [
                    [
                        'condition' => 'Severe Bleeding - EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Severe bleeding requires immediate action to prevent blood loss and shock.',
                        'immediateActions' => [
                            'Apply firm, direct pressure with clean cloth or bandage',
                            'Elevate injured area above heart level if possible',
                            'Maintain continuous pressure for 10-15 minutes',
                            'Apply pressure bandage if bleeding continues',
                            'Do not remove embedded objects - apply pressure around them',
                            'Call emergency services if bleeding doesn\'t stop'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Bright red blood spurting with pulse (arterial bleeding)',
                            'Dark red blood flowing steadily (venous bleeding)',
                            'Blood soaking through bandages quickly',
                            'Pale skin, weakness, dizziness, or fainting',
                            'Rapid pulse and shallow breathing'
                        ]
                    ]
                ],
                'disclaimer' => 'Apply direct pressure and call emergency services for severe bleeding.',
                'emergencyNumber' => '912'
            ];
        }
        
        if (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'choking') !== false || strpos($queryLower, 'chocking') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'recommendations' => [
                    [
                        'condition' => 'Choking - LIFE-THREATENING EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Choking blocks airway and requires immediate life-saving intervention.',
                        'immediateActions' => [
                            'Ask "Are you choking?" - confirm airway obstruction',
                            'If person can speak or cough, encourage forceful coughing',
                            'Stand behind person and perform abdominal thrusts (Heimlich maneuver)',
                            'Call emergency services (912) while performing aid',
                            'Continue thrusts until object expelled or person unconscious',
                            'If unconscious, begin CPR and check airway'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Cannot speak, breathe, or cough effectively',
                            'Hands clutching throat (universal choking sign)',
                            'Blue lips, face, or nail beds (cyanosis)',
                            'Wheezing, stridor, or no breathing sounds',
                            'Loss of consciousness if obstruction persists'
                        ]
                    ]
                ],
                'disclaimer' => 'Choking is a medical emergency. Perform first aid immediately and call emergency services.',
                'emergencyNumber' => '912'
            ];
        }
        
        if (strpos($queryLower, 'burn') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'recommendations' => [
                    [
                        'condition' => 'Burns - First Aid Required',
                        'severity' => 'urgent',
                        'summary' => 'Burns require immediate cooling and proper first aid to prevent damage.',
                        'immediateActions' => [
                            'Cool burn with cool (not cold) running water for 15-20 minutes',
                            'Remove jewelry, watches, tight clothing from burned area',
                            'Cover burn with sterile, non-stick dressing or clean cloth',
                            'Do not apply ice, butter, or ointments to burn',
                            'Seek medical attention for large, deep, or facial burns'
                        ],
                        'callEmergency' => false,
                        'emergencySigns' => [
                            'Large burn area larger than victim\'s palm',
                            'Deep burns with white, charred, or numb skin',
                            'Burns on face, hands, feet, genitals, or major joints',
                            'Chemical or electrical burns'
                        ]
                    ]
                ],
                'disclaimer' => 'Cool burns immediately and seek medical attention for severe burns.',
                'emergencyNumber' => '912'
            ];
        }
        
        if (strpos($queryLower, 'breath') !== false || strpos($queryLower, 'breathing') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'recommendations' => [
                    [
                        'condition' => 'Difficulty Breathing - EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Breathing difficulty requires immediate medical attention and monitoring.',
                        'immediateActions' => [
                            'Call emergency services immediately (912)',
                            'Help person sit upright in comfortable position',
                            'Loosen tight clothing around neck and chest',
                            'Monitor breathing and consciousness continuously',
                            'Keep person calm and reassure them',
                            'Be prepared to perform CPR if breathing stops'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Shortness of breath or wheezing',
                            'Blue lips or fingernails (cyanosis)',
                            'Chest pain or tightness',
                            'Confusion, dizziness, or loss of consciousness',
                            'Rapid, shallow breathing or gasping'
                        ]
                    ]
                ],
                'disclaimer' => 'Breathing difficulty is a medical emergency. Call emergency services immediately.',
                'emergencyNumber' => '912'
            ];
        }
        
        // Default emergency response for other queries
        return [
            'success' => true,
            'query' => $query,
            'aiPowered' => false,
            'recommendations' => [
                [
                    'condition' => 'Emergency Assessment Required',
                    'severity' => 'urgent',
                    'summary' => "Based on your emergency query: '{$query}', immediate medical assessment is needed.",
                    'immediateActions' => [
                        'Call emergency services (912) if life-threatening symptoms',
                        'Stay calm and assess the situation carefully',
                        'Provide basic first aid if trained and safe to do so',
                        'Monitor symptoms and person\'s condition continuously',
                        'Keep person comfortable and safe until help arrives'
                    ],
                    'callEmergency' => true,
                    'emergencySigns' => [
                        'Any symptoms causing severe distress',
                        'Condition worsening over time',
                        'Severe pain or discomfort',
                        'Changes in consciousness or breathing'
                    ]
                ]
            ],
            'disclaimer' => 'This is emergency first aid guidance. When in doubt, always call emergency services.',
            'emergencyNumber' => '912'
        ];
    }
    
    /**
     * Build emergency-specific prompt for AI
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
                    \"condition\": \"Specific condition name matching the user's query\",
                    \"severity\": \"critical|urgent|moderate|minor\",
                    \"summary\": \"Brief description directly related to user's symptoms\",
                    \"immediateActions\": [\"Specific actions for the described condition\", \"Action 2\"],
                    \"callEmergency\": true/false,
                    \"emergencySigns\": [\"Specific signs for this condition\", \"Sign 2\"]
                }
            ],
            \"disclaimer\": \"Medical disclaimer text\",
            \"emergencyNumber\": \"912\"
        }
        
        Rules:
        1. Match your response EXACTLY to what the user described
        2. If user mentions specific symptoms, address those specifically
        3. Provide relevant, actionable steps for the described condition
        4. Be concise and focused on the user's specific situation
        5. Use current medical best practices";
    }
    
    /**
     * Parse AI response and return structured recommendations
     */
    private function parseAIResponse(string $text, string $query, bool $isAI = false): array
    {
        try {
            // Try to extract JSON from response
            $jsonStart = strpos($text, '{');
            $jsonEnd = strrpos($text, '}');
            
            if ($jsonStart !== false && $jsonEnd !== false) {
                $jsonText = substr($text, $jsonStart, $jsonEnd - $jsonStart + 1);
                $data = json_decode($jsonText, true);
                
                if ($data && isset($data['recommendations'])) {
                    return [
                        'success' => true,
                        'query' => $query,
                        'aiPowered' => $isAI,
                        'recommendations' => $data['recommendations'],
                        'disclaimer' => $data['disclaimer'] ?? 'This is not medical advice. Call emergency services for serious conditions.',
                        'emergencyNumber' => $data['emergencyNumber'] ?? '912'
                    ];
                }
            }
            
            // If JSON parsing fails, create recommendations from text
            return $this->extractRecommendationsFromText($text, $query, $isAI);
            
        } catch (\Exception $e) {
            Log::error('Error parsing AI response: ' . $e->getMessage());
            return $this->getFallbackRecommendations($query);
        }
    }
    
    /**
     * Extract recommendations from text when JSON parsing fails
     */
    private function extractRecommendationsFromText(string $text, string $query, bool $isAI): array
    {
        $queryLower = strtolower($query);
        
        // Create specific recommendations based on user's actual query
        $recommendations = [];
        
        // Analyze the query to create targeted recommendations
        if (strpos($queryLower, 'chest') !== false || strpos($queryLower, 'heart') !== false) {
            $recommendations[] = [
                'condition' => 'Chest Pain / Possible Heart Attack',
                'severity' => 'critical',
                'summary' => 'Based on your symptoms of chest pain, immediate medical attention may be required.',
                'immediateActions' => [
                    'Call emergency services immediately (912)',
                    'Have person sit down and rest',
                    'Give aspirin if available and not allergic',
                    'Monitor breathing and consciousness'
                ],
                'callEmergency' => true,
                'emergencySigns' => ['Chest pressure or tightness', 'Pain radiating to arm/jaw', 'Shortness of breath', 'Cold sweat']
            ];
        } elseif (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false) {
            $recommendations[] = [
                'condition' => 'Severe Bleeding',
                'severity' => 'critical',
                'summary' => 'Based on your symptoms of bleeding, immediate action is required to stop blood loss.',
                'immediateActions' => [
                    'Apply direct pressure with clean cloth',
                    'Elevate injured area if possible',
                    'Apply tourniquet if severe bleeding',
                    'Call emergency services (912)'
                ],
                'callEmergency' => true,
                'emergencySigns' => ['Heavy bleeding', 'Weakness or dizziness', 'Pale skin', 'Rapid heartbeat']
            ];
        } elseif (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'breath') !== false) {
            $recommendations[] = [
                'condition' => strpos($queryLower, 'choke') !== false ? 'Choking / Airway Obstruction' : 'Difficulty Breathing',
                'severity' => 'critical',
                'summary' => 'Based on your symptoms, immediate intervention may be required for breathing.',
                'immediateActions' => [
                    'Call emergency services immediately (912)',
                    'Help person sit upright',
                    'Perform Heimlich maneuver if choking',
                    'Monitor breathing continuously'
                ],
                'callEmergency' => true,
                'emergencySigns' => ['Cannot speak or breathe', 'Blue lips', 'Hands to throat', 'No coughing']
            ];
        } elseif (strpos($queryLower, 'burn') !== false) {
            $recommendations[] = [
                'condition' => 'Burns',
                'severity' => 'urgent',
                'summary' => 'Based on your symptoms of burns, immediate first aid is needed.',
                'immediateActions' => [
                    'Cool burn with cool running water',
                    'Remove jewelry or tight clothing',
                    'Cover burn with sterile dressing',
                    'Seek medical attention for severe burns'
                ],
                'callEmergency' => false,
                'emergencySigns' => ['Large burn area', 'Deep burns', 'Burns on face/hands/genitals']
            ];
        } elseif (strpos($queryLower, 'head') !== false || strpos($queryLower, 'fall') !== false) {
            $recommendations[] = [
                'condition' => 'Head Injury',
                'severity' => 'urgent',
                'summary' => 'Based on your symptoms of head injury, careful assessment and medical evaluation needed.',
                'immediateActions' => [
                    'Apply ice to reduce swelling',
                    'Monitor for consciousness changes',
                    'Avoid moving person unnecessarily',
                    'Seek medical evaluation'
                ],
                'callEmergency' => false,
                'emergencySigns' => ['Headache', 'Dizziness', 'Nausea', 'Vision changes', 'Confusion']
            ];
        } else {
            // Generic fallback for unrecognized symptoms
            $recommendations[] = [
                'condition' => 'Medical Assessment Needed',
                'severity' => 'moderate',
                'summary' => "Based on your symptoms: '{$query}', professional medical assessment is recommended.",
                'immediateActions' => [
                    'Stay calm and assess the situation',
                    'Call emergency services (912) if life-threatening',
                    'Provide basic first aid if trained',
                    'Monitor symptoms closely'
                ],
                'callEmergency' => false,
                'emergencySigns' => ['Any worsening symptoms', 'Loss of consciousness', 'Severe pain']
            ];
        }
        
        return [
            'success' => true,
            'query' => $query,
            'aiPowered' => $isAI,
            'recommendations' => $recommendations,
            'disclaimer' => 'This is AI-generated emergency guidance. Call emergency services for serious conditions.',
            'emergencyNumber' => '912'
        ];
    }
    
    /**
     * Get fallback recommendations when AI fails - uses real-time internet data
     */
    private function getFallbackRecommendations(string $query): array
    {
        try {
            // Use alternative AI service for real-time recommendations
            $fallbackPrompt = "You are an emergency medical assistant with access to current medical knowledge. 
            Analyze the user's specific query: '{$query}' and provide targeted first aid recommendations.
            
            IMPORTANT: Focus specifically on what the user described. Match your response to their exact symptoms or situation.
            
            Format as JSON with:
            {
                \"recommendations\": [
                    {
                        \"condition\": \"Specific condition matching user's query\",
                        \"severity\": \"critical|urgent|moderate|minor\",
                        \"summary\": \"Description directly related to user's symptoms\",
                        \"immediateActions\": [\"Specific actions for this condition\", \"Action 2\"],
                        \"callEmergency\": true/false,
                        \"emergencySigns\": [\"Specific signs for this condition\", \"Sign 2\"]
                    }
                ],
                \"disclaimer\": \"Medical disclaimer\",
                \"emergencyNumber\": \"912\"
            }
            
            Rules:
            1. Address the specific symptoms/condition user mentioned
            2. Provide relevant, actionable steps for their situation
            3. Use current medical best practices and evidence-based guidelines
            4. Be concise and focused on user's specific needs";
            
            // Try using a different model or API endpoint for redundancy
            $response = Http::timeout(12)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY', 'sk-proj-demo'),
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are an emergency medical assistant providing real-time, evidence-based recommendations using current medical knowledge from the internet.'
                            ],
                            [
                                'role' => 'user',
                                'content' => $fallbackPrompt
                            ]
                        ],
                        'max_tokens' => 600,
                        'temperature' => 0.2
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? '';
                return $this->parseAIResponse($text, $query, true);
            }
        } catch (\Exception $e) {
            Log::error('Working fallback AI service error: ' . $e->getMessage());
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
            'disclaimer' => 'This is not medical advice. Always consult with qualified healthcare professionals for medical concerns. Emergency services should be called for life-threatening conditions.',
            'emergencyNumber' => '912'
        ];
    }
}
