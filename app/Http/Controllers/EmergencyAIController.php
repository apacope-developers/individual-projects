<?php

namespace App\Http\Controllers;

use App\Services\WorkingAIRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EmergencyAIController extends Controller
{
    private $aiService;

    public function __construct(WorkingAIRecommendationService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get AI-powered emergency recommendations
     */
    public function getRecommendations(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:500'
        ]);

        try {
            $recommendations = $this->aiService->getEmergencyRecommendations($request->input('query'));
            
            return response()->json([
                'success' => true,
                'data' => $recommendations
            ]);
        } catch (\Exception $e) {
            Log::error('Emergency AI controller error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to get recommendations at this time. Please try again.',
                'data' => $this->aiService->getFallbackRecommendations($request->input('query'))
            ], 500);
        }
    }

    /**
     * Emergency fallback response
     */
    private function getEmergencyFallback(): array
    {
        return [
            'success' => true,
            'query' => '',
            'aiPowered' => false,
            'recommendations' => [
                [
                    'condition' => 'Emergency Assessment Required',
                    'severity' => 'critical',
                    'summary' => 'AI service temporarily unavailable. Please assess the situation manually.',
                    'immediateActions' => [
                        'Call emergency services (912) if life-threatening',
                        'Check consciousness and breathing',
                        'Provide basic first aid as needed',
                        'Stay calm and monitor the person'
                    ],
                    'callEmergency' => true,
                    'emergencySigns' => ['Unconsciousness', 'No breathing', 'Severe bleeding', 'Chest pain']
                ]
            ],
            'disclaimer' => 'This is not medical advice. Call emergency services for serious conditions.',
            'emergencyNumber' => '912'
        ];
    }
}
