<?php

namespace App\Http\Controllers;

use App\Services\WorkingAIRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PublicEmergencyController extends Controller
{
    protected $aiService;

    public function __construct(WorkingAIRecommendationService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get emergency recommendations without authentication
     * This is a public endpoint for life-saving emergency assistance
     */
    public function getEmergencyRecommendations(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:500'
        ]);

        try {
            $query = $request->input('query');
            Log::info('Public emergency query received: ' . $query);
            
            // Get AI recommendations
            $recommendations = $this->aiService->getEmergencyRecommendations($query);
            
            // Add public access flag and emergency emphasis
            $recommendations['publicAccess'] = true;
            $recommendations['emergencyMode'] = true;
            $recommendations['timestamp'] = now()->toISOString();
            
            return response()->json([
                'success' => true,
                'data' => $recommendations,
                'message' => 'Emergency assistance provided immediately'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Public emergency controller error: ' . $e->getMessage());
            
            // Always provide emergency fallback for public access
            $fallbackRecommendations = $this->getEmergencyFallback($request->input('query'));
            
            return response()->json([
                'success' => true,
                'data' => $fallbackRecommendations,
                'message' => 'Emergency assistance provided via fallback system'
            ]);
        }
    }

    /**
     * Emergency fallback recommendations for critical situations
     */
    private function getEmergencyFallback(string $query): array
    {
        $queryLower = strtolower($query);
        
        // Enhanced emergency logic for public access
        if (strpos($queryLower, 'chest') !== false || strpos($queryLower, 'heart') !== false || strpos($queryLower, 'heart attack') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'publicAccess' => true,
                'emergencyMode' => true,
                'recommendations' => [
                    [
                        'condition' => 'Possible Heart Attack - EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Chest pain could indicate a heart attack. Call emergency services immediately.',
                        'immediateActions' => [
                            'Call emergency services NOW (912)',
                            'Have person sit down and rest',
                            'Give aspirin if available and not allergic',
                            'Loosen tight clothing',
                            'Monitor breathing and consciousness',
                            'Be prepared to perform CPR if needed'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Chest pressure/tightness',
                            'Pain radiating to arm/jaw/back',
                            'Shortness of breath',
                            'Cold sweat, nausea, lightheadedness'
                        ]
                    ]
                ],
                'disclaimer' => 'This is emergency first aid guidance. Call emergency services immediately for heart attack symptoms.',
                'emergencyNumber' => '912',
                'timestamp' => now()->toISOString()
            ];
        }
        
        if (strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'blood') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'publicAccess' => true,
                'emergencyMode' => true,
                'recommendations' => [
                    [
                        'condition' => 'Severe Bleeding - EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Severe bleeding requires immediate action to prevent blood loss.',
                        'immediateActions' => [
                            'Apply firm pressure with clean cloth',
                            'Elevate injured area above heart if possible',
                            'Maintain pressure until bleeding stops',
                            'Apply pressure bandage if available',
                            'Call emergency services if bleeding continues'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Heavy bleeding that won\'t stop',
                            'Pale skin, weakness, dizziness',
                            'Rapid pulse, shallow breathing',
                            'Loss of consciousness'
                        ]
                    ]
                ],
                'disclaimer' => 'Apply pressure and call emergency services for severe bleeding.',
                'emergencyNumber' => '912',
                'timestamp' => now()->toISOString()
            ];
        }
        
        if (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'choking') !== false || strpos($queryLower, 'chocking') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'publicAccess' => true,
                'emergencyMode' => true,
                'recommendations' => [
                    [
                        'condition' => 'Choking - LIFE-THREATENING EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Choking blocks airway and requires immediate action.',
                        'immediateActions' => [
                            'Ask "Are you choking?" - if they can speak/cough, encourage coughing',
                            'If unable to speak/breathe, perform Heimlich maneuver immediately',
                            'Call emergency services (912) while performing aid',
                            'Continue until object expelled or person becomes unconscious',
                            'If unconscious, begin CPR and call emergency services'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Cannot speak or breathe',
                            'Blue lips and skin (cyanosis)',
                            'Hands clutching throat (universal choking sign)',
                            'No coughing or wheezing sounds'
                        ]
                    ]
                ],
                'disclaimer' => 'Choking is a medical emergency. Perform first aid immediately and call emergency services.',
                'emergencyNumber' => '912',
                'timestamp' => now()->toISOString()
            ];
        }
        
        if (strpos($queryLower, 'burn') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'publicAccess' => true,
                'emergencyMode' => true,
                'recommendations' => [
                    [
                        'condition' => 'Burns - First Aid Required',
                        'severity' => 'urgent',
                        'summary' => 'Burns require immediate cooling and proper first aid.',
                        'immediateActions' => [
                            'Cool burn with cool (not cold) running water for 10-20 minutes',
                            'Remove jewelry, watches, tight clothing from burned area',
                            'Cover burn with sterile, non-stick dressing or clean cloth',
                            'Seek medical attention for large, deep, or critical area burns'
                        ],
                        'callEmergency' => false,
                        'emergencySigns' => [
                            'Large burn area (larger than victim\'s palm)',
                            'Deep burns (white, charred, or numb)',
                            'Burns on face, hands, feet, genitals, or major joints',
                            'Chemical or electrical burns'
                        ]
                    ]
                ],
                'disclaimer' => 'Cool burns immediately and seek medical attention for severe burns.',
                'emergencyNumber' => '912',
                'timestamp' => now()->toISOString()
            ];
        }
        
        if (strpos($queryLower, 'breath') !== false || strpos($queryLower, 'breathing') !== false) {
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'publicAccess' => true,
                'emergencyMode' => true,
                'recommendations' => [
                    [
                        'condition' => 'Difficulty Breathing - EMERGENCY',
                        'severity' => 'critical',
                        'summary' => 'Breathing difficulty requires immediate medical attention.',
                        'immediateActions' => [
                            'Call emergency services immediately (912)',
                            'Help person sit upright in comfortable position',
                            'Loosen tight clothing around neck and chest',
                            'Monitor breathing and consciousness continuously',
                            'Be prepared to perform CPR if breathing stops'
                        ],
                        'callEmergency' => true,
                        'emergencySigns' => [
                            'Shortness of breath or wheezing',
                            'Blue lips or fingernails',
                            'Chest pain or tightness',
                            'Confusion or loss of consciousness'
                        ]
                    ]
                ],
                'disclaimer' => 'Breathing difficulty is a medical emergency. Call emergency services immediately.',
                'emergencyNumber' => '912',
                'timestamp' => now()->toISOString()
            ];
        }
        
        // Default emergency response
        return [
            'success' => true,
            'query' => $query,
            'aiPowered' => false,
            'publicAccess' => true,
            'emergencyMode' => true,
            'recommendations' => [
                [
                    'condition' => 'Emergency Assessment Required',
                    'severity' => 'urgent',
                    'summary' => "Based on your emergency query: '{$query}', immediate assessment is needed.",
                    'immediateActions' => [
                        'Call emergency services (912) if life-threatening',
                        'Stay calm and assess the situation',
                        'Provide basic first aid if trained',
                        'Monitor symptoms and person\'s condition',
                        'Keep person comfortable and safe'
                    ],
                    'callEmergency' => true,
                    'emergencySigns' => [
                        'Any symptoms that concern you',
                        'Condition worsening over time',
                        'Severe pain or discomfort',
                        'Changes in consciousness or breathing'
                    ]
                ]
            ],
            'disclaimer' => 'This is emergency first aid guidance. When in doubt, always call emergency services.',
            'emergencyNumber' => '912',
            'timestamp' => now()->toISOString()
        ];
    }

    /**
     * Get emergency hotline information
     */
    public function getEmergencyHotline(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'emergencyNumber' => '912',
                'alternativeNumbers' => [
                    'Ambulance: 912',
                    'Police: 911',
                    'Fire Department: 911'
                ],
                'message' => 'Call 912 for medical emergencies. Call 911 for police and fire emergencies.',
                'disclaimer' => 'In life-threatening situations, call emergency services immediately.'
            ]
        ]);
    }
}
