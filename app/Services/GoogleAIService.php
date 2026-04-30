<?php

namespace App\Services;

use Gemini\Client;
use Gemini\Enums\Role;
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
                $this->client = new Client($this->apiKey);
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
     * Fallback recommendations when AI is unavailable
     */
    private function getFallbackRecommendation(string $symptoms): array
    {
        $symptoms = strtolower($symptoms);
        
        // Basic keyword matching for common emergencies
        if (str_contains($symptoms, 'chest') && str_contains($symptoms, 'pain')) {
            return [
                'emergency_level' => 'critical',
                'condition_name' => 'Possible Heart Attack',
                'immediate_action' => 'Call emergency services immediately',
                'steps' => [
                    'Call 912 or local emergency number',
                    'Have the person sit down and rest',
                    'Give aspirin if available and person is not allergic',
                    'Loosen tight clothing',
                    'Monitor breathing and consciousness',
                    'Be prepared to perform CPR if needed'
                ],
                'emergency_call' => true,
                'warning_signs' => ['Chest pain spreading to arm/jaw', 'Shortness of breath', 'Sweating', 'Nausea'],
                'important_notes' => ['Do not delay calling emergency services', 'Do not give food or drink']
            ];
        }
        
        if (str_contains($symptoms, 'bleed') || str_contains($symptoms, 'blood')) {
            return [
                'emergency_level' => 'urgent',
                'condition_name' => 'Severe Bleeding',
                'immediate_action' => 'Apply direct pressure',
                'steps' => [
                    'Apply firm pressure with clean cloth',
                    'Elevate injured area above heart',
                    'Maintain pressure until bleeding stops',
                    'Apply pressure bandage if available',
                    'Seek medical attention if bleeding continues'
                ],
                'emergency_call' => false,
                'warning_signs' => ['Bleeding doesn\'t stop after 10 minutes', 'Large amount of blood loss', 'Signs of shock'],
                'important_notes' => ['Do not remove objects from wound', 'Keep person warm']
            ];
        }

        // Default fallback
        return [
            'emergency_level' => 'moderate',
            'condition_name' => 'Medical Emergency',
            'immediate_action' => 'Monitor and seek medical advice',
            'steps' => [
                'Stay calm and assess the situation',
                'Ensure the person is comfortable',
                'Monitor vital signs',
                'Call emergency services if condition worsens',
                'Provide basic comfort measures'
            ],
            'emergency_call' => false,
            'warning_signs' => ['Condition worsens', 'Person becomes unconscious', 'Difficulty breathing'],
            'important_notes' => ['This is basic guidance - seek professional medical help']
        ];
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
     * Fallback suggestions when AI is unavailable
     */
    private function getFallbackSuggestions(string $query): array
    {
        $query = strtolower($query);
        $suggestions = [];

        if (str_contains($query, 'chest') || str_contains($query, 'heart')) {
            $suggestions[] = 'Heart Attack';
            $suggestions[] = 'Cardiac Arrest';
        }
        if (str_contains($query, 'bleed') || str_contains($query, 'blood')) {
            $suggestions[] = 'Severe Bleeding';
            $suggestions[] = 'Wound Care';
        }
        if (str_contains($query, 'choke') || str_contains($query, 'breath')) {
            $suggestions[] = 'Choking';
            $suggestions[] = 'Breathing Difficulty';
        }
        if (str_contains($query, 'burn')) {
            $suggestions[] = 'Burns';
        }
        if (str_contains($query, 'break') || str_contains($query, 'fracture')) {
            $suggestions[] = 'Fractures';
        }

        return empty($suggestions) ? ['General Emergency'] : $suggestions;
    }
}
