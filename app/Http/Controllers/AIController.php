<?php

namespace App\Http\Controllers;

use App\Services\MultiAIService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIController extends Controller
{
    protected $aiService;

    public function __construct(MultiAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Get AI-powered first aid recommendations
     */
    public function getFirstAidRecommendation(Request $request): JsonResponse
    {
        $request->validate([
            'symptoms' => 'required|string|min:3|max:500'
        ]);

        try {
            $recommendation = $this->aiService->getFirstAidRecommendation($request->symptoms);
            
            return response()->json([
                'success' => true,
                'data' => $recommendation,
                'ai_powered' => true
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to get AI recommendation at this time',
                'data' => $this->getEmergencyFallback($request->symptoms)
            ], 500);
        }
    }

    /**
     * Get emergency search suggestions
     */
    public function getEmergencySuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100'
        ]);

        try {
            $suggestions = $this->aiService->getEmergencySuggestions($request->query);
            
            return response()->json([
                'success' => true,
                'suggestions' => $suggestions,
                'ai_powered' => true
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'suggestions' => ['Heart Attack', 'Severe Bleeding', 'Choking', 'Burns', 'Fractures']
            ], 500);
        }
    }

    /**
     * Voice-to-text processing endpoint
     */
    public function processVoice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'audio_data' => 'required|string', // Base64 encoded audio
            'format' => 'sometimes|string|in:webm,wav,mp3'
        ]);

        try {
            // For now, we'll simulate voice-to-text processing
            // In a real implementation, you would use a service like Google Speech-to-Text
            $transcribedText = $this->simulateVoiceToText($validated['audio_data']);
            
            return response()->json([
                'success' => true,
                'text' => $transcribedText,
                'confidence' => 0.95
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Voice processing failed'
            ], 500);
        }
    }

    /**
     * Simulate voice-to-text conversion
     * In production, replace with actual Google Speech-to-Text API
     */
    private function simulateVoiceToText(string $audioData): string
    {
        // This is a placeholder implementation
        // In production, you would:
        // 1. Decode base64 audio data
        // 2. Send to Google Speech-to-Text API
        // 3. Return the transcribed text
        
        // For demo purposes, return some common emergency queries
        $sampleQueries = [
            'chest pain and difficulty breathing',
            'severe bleeding from arm',
            'person is choking and cant breathe',
            'burn on hand from hot water',
            'fell and might have broken leg',
            'person is unconscious',
            'snake bite on leg',
            'having trouble breathing',
            'severe allergic reaction',
            'head injury after fall'
        ];
        
        return $sampleQueries[array_rand($sampleQueries)];
    }

    /**
     * Check available AI providers
     */
    public function getProviders(): JsonResponse
    {
        try {
            $providers = $this->aiService->getAvailableProviders();
            
            return response()->json([
                'success' => true,
                'providers' => $providers,
                'message' => empty($providers) ? 'No AI providers configured' : 'AI providers available'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking AI providers',
                'providers' => []
            ], 500);
        }
    }

    /**
     * Emergency fallback when AI is unavailable
     */
    private function getEmergencyFallback(string $symptoms): array
    {
        return [
            'emergency_level' => 'moderate',
            'condition_name' => 'Medical Emergency',
            'immediate_action' => 'Call emergency services if severe',
            'steps' => [
                'Stay calm and assess the situation',
                'Ensure person is comfortable',
                'Monitor breathing and consciousness',
                'Call 912 if condition worsens',
                'Provide basic first aid if trained'
            ],
            'emergency_call' => false,
            'warning_signs' => ['Difficulty breathing', 'Loss of consciousness', 'Severe pain'],
            'important_notes' => ['This is basic guidance - seek professional medical help']
        ];
    }
}
