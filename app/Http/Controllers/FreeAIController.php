<?php

namespace App\Http\Controllers;

use App\Services\FreeAIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FreeAIController extends Controller
{
    protected $freeAIService;

    public function __construct(FreeAIService $freeAIService)
    {
        $this->freeAIService = $freeAIService;
    }

    /**
     * Get AI-powered first aid recommendations using free providers
     */
    public function getFirstAidRecommendation(Request $request): JsonResponse
    {
        $request->validate([
            'symptoms' => 'required|string|min:3|max:500'
        ]);

        try {
            $recommendation = $this->freeAIService->getFirstAidRecommendation($request->input('symptoms'));
            
            return response()->json([
                'success' => true,
                'recommendation' => $recommendation,
                'provider' => 'free_ai',
                'message' => 'Free AI recommendation generated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate AI recommendation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get emergency suggestions using free providers
     */
    public function getEmergencySuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        try {
            $suggestions = $this->freeAIService->getEmergencySuggestions($request->input('query'));
            
            return response()->json([
                'success' => true,
                'suggestions' => $suggestions,
                'provider' => 'free_ai',
                'message' => 'Free AI suggestions generated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate AI suggestions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check available free AI providers
     */
    public function getProviders(): JsonResponse
    {
        try {
            $providers = $this->freeAIService->getAvailableProviders();
            
            return response()->json([
                'success' => true,
                'providers' => $providers,
                'message' => empty($providers) ? 'No free AI providers configured' : 'Free AI providers available'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking free AI providers',
                'providers' => []
            ], 500);
        }
    }
}
