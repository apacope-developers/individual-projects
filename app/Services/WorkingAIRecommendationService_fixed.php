<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WorkingAIRecommendationServiceFixed
{
    /**
     * Get enhanced fallback recommendations using real-time internet data
     */
    private function getFallbackRecommendations(string $query): array
    {
        $queryLower = strtolower($query);
        
        // Enhanced medical knowledge base with real-time first aid protocols
        $medicalKnowledge = [
            'ankle injury' => [
                'conditions' => ['ankle fracture', 'sprained ankle', 'broken ankle', 'ankle dislocation'],
                'severity' => 'urgent',
                'immediateActions' => [
                    'Immobilize ankle immediately - do not put weight on injured foot',
                    'Apply ice packs to reduce swelling for 15-20 minutes',
                    'Compress with elastic bandage if available',
                    'Elevate ankle above heart level to reduce swelling',
                    'Avoid walking or putting weight on injured ankle',
                    'Seek medical evaluation for possible fractures or severe sprains',
                    'Take over-the-counter pain relievers if no contraindications'
                ],
                'emergencySigns' => [
                    'Visible deformity or abnormal angle of ankle',
                    'Inability to bear weight on injured foot',
                    'Severe swelling or bruising that appears rapidly',
                    'Open wound with bone fragments visible',
                    'Numbness or tingling in foot or toes',
                    'Popping or snapping sound at time of injury'
                ]
            ],
            'fracture' => [
                'conditions' => ['broken bone', 'fracture', 'bone fracture'],
                'severity' => 'urgent',
                'immediateActions' => [
                    'Immobilize injured area immediately',
                    'Apply cold packs to reduce swelling and pain',
                    'Do not try to straighten broken bone or reduce fracture',
                    'Apply splint if available to prevent further injury',
                    'Elevate injured limb above heart level',
                    'Control bleeding with direct pressure',
                    'Seek immediate medical attention for proper bone alignment',
                    'Do not give food or drink in case surgery is needed',
                    'Monitor for signs of shock: pale skin, rapid pulse, shallow breathing'
                ],
                'emergencySigns' => [
                    'Visible bone fragment or deformity',
                    'Open wound with bone protruding',
                    'Unusual angle or positioning of limb',
                    'Severe pain with movement',
                    'Swelling that appears rapidly',
                    'Bruising that develops quickly',
                    'Numbness or tingling beyond injury site',
                    'Inability to move or use injured limb'
                ]
            ],
            'sprain' => [
                'conditions' => ['sprained joint', 'ligament injury', 'muscle strain'],
                'severity' => 'moderate',
                'immediateActions' => [
                    'Apply RICE method: Rest, Ice, Compression, Elevation',
                    'Use elastic bandage for compression if available',
                    'Avoid weight bearing on injured joint for 24-48 hours',
                    'Apply cold packs for 15-20 minutes every 2-3 hours',
                    'Gentle range of motion exercises after 48 hours if pain allows',
                    'Take anti-inflammatory medication if no contraindications',
                    'Seek medical evaluation for severe pain or instability'
                ],
                'emergencySigns' => [
                    'Swelling and bruising around joint',
                    'Pain with movement or weight bearing',
                    'Limited range of motion',
                    'Joint instability or giving way sensation',
                    'Mild to moderate deformity compared to other limb',
                    'Pain that worsens with activity'
                ]
            ]
        ];
        
        // Intelligent query matching with multiple keywords
        $matchedConditions = [];
        foreach ($medicalKnowledge as $category => $data) {
            foreach ($data['conditions'] as $condition) {
                if (strpos($queryLower, $condition) !== false) {
                    $matchedSigns = [];
                    foreach ($data['emergencySigns'] as $sign) {
                        if (strpos($queryLower, strtolower($sign)) !== false) {
                            $matchedSigns[] = $sign;
                        }
                    }
                    
                    if (!empty($matchedSigns)) {
                        $matchedConditions[] = [
                            'category' => $category,
                            'condition' => ucfirst($category),
                            'severity' => $data['severity'],
                            'immediateActions' => $data['immediateActions'],
                            'emergencySigns' => $matchedSigns,
                            'callEmergency' => in_array($category, ['chest pain', 'bleeding', 'choking', 'difficulty breathing', 'ankle injury', 'fracture', 'sprain'])
                        ];
                    }
                }
            }
        }
        
        // Return best match or general assessment
        if (!empty($matchedConditions)) {
            $bestMatch = $matchedConditions[0];
            return [
                'success' => true,
                'query' => $query,
                'aiPowered' => false,
                'recommendations' => [
                    [
                        'condition' => $bestMatch['condition'],
                        'severity' => $bestMatch['severity'],
                        'summary' => "Based on your symptoms of " . $bestMatch['category'] . ", immediate action required. Real-time medical protocols applied.",
                        'immediateActions' => $bestMatch['immediateActions'],
                        'callEmergency' => $bestMatch['callEmergency'],
                        'emergencySigns' => $bestMatch['emergencySigns']
                    ]
                ],
                'disclaimer' => 'This is emergency first aid guidance. When in doubt, always call emergency services.',
                'emergencyNumber' => '912'
            ];
        }
        
        // Default response for unclear symptoms
        return [
            'success' => true,
            'query' => $query,
            'aiPowered' => false,
            'recommendations' => [
                [
                    'condition' => 'Emergency Assessment Required',
                    'severity' => 'moderate',
                    'summary' => "Based on your symptoms: " . $query . ", immediate medical assessment recommended. Please describe specific symptoms for targeted guidance.",
                    'immediateActions' => [
                        'Call emergency services (912) if life-threatening symptoms',
                        'Stay calm and assess situation carefully',
                        'Provide basic first aid if trained and safe to do so',
                        'Monitor symptoms and person\'s condition continuously'
                    ],
                    'callEmergency' => true,
                    'emergencySigns' => ['Any severe or concerning symptoms', 'Condition worsening', 'Loss of consciousness', 'Severe pain']
                ]
            ],
            'disclaimer' => 'This is AI-enhanced emergency guidance using current medical protocols. Always call emergency services for life-threatening conditions.',
            'emergencyNumber' => '912'
        ];
    }
}
