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
                'Ensure person is comfortable',
                'Monitor vital signs',
                'Call 912 if condition worsens',
                'Provide basic first aid if trained'
            ],
            'emergency_call' => false,
            'warning_signs' => ['Condition worsens', 'Person becomes unconscious', 'Difficulty breathing'],
            'important_notes' => ['This is basic guidance - seek professional medical help']
        ];
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

    /**
     * Get available providers information
     */
    public function getAvailableProviders(): array
    {
        return $this->availableProviders;
    }
}
