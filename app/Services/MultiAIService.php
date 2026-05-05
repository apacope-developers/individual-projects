<?php

namespace App\Services;

use Gemini\Client as GeminiClient;
use Gemini;
use OpenAI;
use Illuminate\Support\Facades\Log;

class MultiAIService
{
    protected $geminiClient;
    protected $openaiClient;
    protected $availableProviders;

    public function __construct()
    {
        $this->initializeProviders();
    }

    /**
     * Initialize available AI providers
     */
    private function initializeProviders()
    {
        $this->availableProviders = [];

        // Initialize Gemini
        $geminiApiKey = config('services.gemini.api_key');
        if ($geminiApiKey) {
            try {
                $this->geminiClient = Gemini::client($geminiApiKey);
                $this->availableProviders[] = 'gemini';
                Log::info('Gemini provider initialized');
            } catch (\Exception $e) {
                Log::error('Failed to initialize Gemini: ' . $e->getMessage());
            }
        }

        // Initialize OpenAI
        $openaiApiKey = config('services.openai.api_key');
        if ($openaiApiKey) {
            try {
                $this->openaiClient = OpenAI::client($openaiApiKey);
                $this->availableProviders[] = 'openai';
                Log::info('OpenAI provider initialized');
            } catch (\Exception $e) {
                Log::error('Failed to initialize OpenAI: ' . $e->getMessage());
            }
        }

        if (empty($this->availableProviders)) {
            Log::warning('No AI providers available - using fallback only');
        }
    }

    /**
     * Get AI-powered first aid recommendations using available providers
     */
    public function getFirstAidRecommendation(string $symptoms): array
    {
        if (empty($this->availableProviders)) {
            Log::warning('No AI providers available - using fallback');
            return $this->getFallbackRecommendation($symptoms);
        }

        // Try providers in order of preference
        foreach (['openai', 'gemini'] as $provider) {
            if (in_array($provider, $this->availableProviders)) {
                try {
                    $result = $this->getRecommendationFromProvider($provider, $symptoms);
                    if ($result) {
                        Log::info("Got recommendation from {$provider} provider");
                        return $result;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to get recommendation from {$provider}: " . $e->getMessage());
                    continue;
                }
            }
        }

        // All providers failed, use fallback
        Log::warning('All AI providers failed - using fallback');
        return $this->getFallbackRecommendation($symptoms);
    }

    /**
     * Get recommendation from specific provider
     */
    private function getRecommendationFromProvider(string $provider, string $symptoms): array
    {
        switch ($provider) {
            case 'openai':
                return $this->getOpenAIRecommendation($symptoms);
            case 'gemini':
                return $this->getGeminiRecommendation($symptoms);
            default:
                throw new \Exception("Unknown provider: {$provider}");
        }
    }

    /**
     * Get recommendation from OpenAI
     */
    private function getOpenAIRecommendation(string $symptoms): array
    {
        $prompt = $this->buildFirstAidPrompt($symptoms);

        $response = $this->openaiClient->chat()->create([
            'model' => 'gpt-3.5-turbo',
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

        $text = $response->choices[0]->message->content;
        return $this->parseFirstAidResponse($text);
    }

    /**
     * Get recommendation from Gemini
     */
    private function getGeminiRecommendation(string $symptoms): array
    {
        $prompt = $this->buildFirstAidPrompt($symptoms);

        $response = $this->geminiClient
            ->generativeModel('gemini-1.5-flash')
            ->generateContent($prompt);

        $text = $response->text();
        return $this->parseFirstAidResponse($text);
    }

    /**
     * Get emergency suggestions from available providers
     */
    public function getEmergencySuggestions(string $query): array
    {
        if (empty($this->availableProviders)) {
            Log::warning('No AI providers available for suggestions - using fallback');
            return $this->getFallbackSuggestions($query);
        }

        // Try providers in order of preference
        foreach (['openai', 'gemini'] as $provider) {
            if (in_array($provider, $this->availableProviders)) {
                try {
                    $result = $this->getSuggestionsFromProvider($provider, $query);
                    if ($result) {
                        Log::info("Got suggestions from {$provider} provider");
                        return $result;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to get suggestions from {$provider}: " . $e->getMessage());
                    continue;
                }
            }
        }

        // All providers failed, use fallback
        Log::warning('All AI providers failed for suggestions - using fallback');
        return $this->getFallbackSuggestions($query);
    }

    /**
     * Get suggestions from specific provider
     */
    private function getSuggestionsFromProvider(string $provider, string $query): array
    {
        switch ($provider) {
            case 'openai':
                return $this->getOpenAISuggestions($query);
            case 'gemini':
                return $this->getGeminiSuggestions($query);
            default:
                throw new \Exception("Unknown provider: {$provider}");
        }
    }

    /**
     * Get suggestions from OpenAI
     */
    private function getOpenAISuggestions(string $query): array
    {
        $prompt = "Analyze the user's specific emergency query: '{$query}' and suggest 3-5 possible emergency conditions that exactly match what they described.
        
        IMPORTANT: Focus specifically on the symptoms/conditions mentioned. If they mention 'chest pain', suggest conditions related to chest pain. If they mention 'bleeding', suggest bleeding-related conditions.
        
        Return only the specific condition names, one per line, without numbering. Match your suggestions to exactly what the user described.";

        $response = $this->openaiClient->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a medical assistant. Provide specific emergency condition suggestions that match the user\'s exact symptoms.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'max_tokens' => 100,
            'temperature' => 0.3
        ]);

        $text = $response->choices[0]->message->content;
        $suggestions = array_filter(array_map('trim', explode("\n", $text)));
        
        return array_slice($suggestions, 0, 5);
    }

    /**
     * Get suggestions from Gemini
     */
    private function getGeminiSuggestions(string $query): array
    {
        $prompt = "Analyze the user's specific emergency query: '{$query}' and suggest 3-5 possible emergency conditions that exactly match what they described.
        
        IMPORTANT: Focus specifically on symptoms/conditions mentioned. If they mention 'chest pain', suggest conditions related to chest pain. If they mention 'bleeding', suggest bleeding-related conditions.
        
        Return only specific condition names, one per line, without numbering. Match your suggestions to exactly what the user described.";

        $response = $this->geminiClient
            ->generativeModel('gemini-1.5-flash')
            ->generateContent($prompt);

        $text = $response->text();
        $suggestions = array_filter(array_map('trim', explode("\n", $text)));
        
        return array_slice($suggestions, 0, 5);
    }

    /**
     * Build the prompt for first aid recommendations
     */
    private function buildFirstAidPrompt(string $symptoms): string
    {
        return "You are a professional first aid and emergency medical assistant. Analyze the user's specific symptoms: '{$symptoms}' and provide targeted first aid guidance.

IMPORTANT: Focus specifically on what the user described. If they mention 'chest pain', address chest pain specifically. If they mention 'bleeding', focus on bleeding emergencies.

Please provide a structured response with the following format:
1. EMERGENCY_LEVEL: (critical/urgent/moderate/minor)
2. CONDITION_NAME: (specific condition name matching user's symptoms)
3. IMMEDIATE_ACTION: (single most important immediate action for their specific situation)
4. STEPS: (numbered list of specific first aid steps for their condition, maximum 8 steps)
5. EMERGENCY_CALL: (true/false - whether to call emergency services immediately for this condition)
6. WARNING_SIGNS: (specific symptoms that indicate worsening of their condition)
7. IMPORTANT_NOTES: (any critical warnings or additional information for their situation)

Rules:
1. Address the specific symptoms/condition the user described
2. Provide relevant, actionable steps for their particular situation
3. Be concise, medically accurate, and focused on immediate first aid actions
4. Do not provide definitive medical diagnoses - this is first aid guidance only
5. Use current medical best practices and evidence-based guidelines";
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
            
            if (preg_match('/1\.\s*EMERGENCY_LEVEL:\s*(.+)/i', $line, $matches)) {
                $result['emergency_level'] = strtolower(trim($matches[1]));
            } elseif (preg_match('/2\.\s*CONDITION_NAME:\s*(.+)/i', $line, $matches)) {
                $result['condition_name'] = trim($matches[1]);
            } elseif (preg_match('/3\.\s*IMMEDIATE_ACTION:\s*(.+)/i', $line, $matches)) {
                $result['immediate_action'] = trim($matches[1]);
            } elseif (preg_match('/4\.\s*STEPS:/i', $line)) {
                // Steps will be collected in the following lines
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
     * Fallback recommendations when AI is unavailable - uses real-time data
     */
    private function getFallbackRecommendation(string $symptoms): array
    {
        try {
            // Try to use a free API for real-time medical guidance
            $prompt = "You are an emergency medical assistant with access to current medical knowledge. 
            Analyze the user's specific symptoms: '{$symptoms}' and provide targeted immediate first aid guidance.
            
            IMPORTANT: Focus specifically on what the user described. If they mention 'chest pain', address chest pain specifically. If they mention 'bleeding', focus on bleeding emergencies.
            
            Format your response exactly as:
            1. EMERGENCY_LEVEL: (critical/urgent/moderate/minor)
            2. CONDITION_NAME: (specific condition name matching user's symptoms)
            3. IMMEDIATE_ACTION: (single most important immediate action for their situation)
            4. STEPS: (numbered list of specific first aid steps for their condition, maximum 8 steps)
            5. EMERGENCY_CALL: (true/false - whether to call emergency services immediately for this condition)
            6. WARNING_SIGNS: (specific symptoms that indicate worsening of their condition)
            7. IMPORTANT_NOTES: (any critical warnings or additional information for their situation)
            
            Rules:
            1. Address the specific symptoms/condition the user described
            2. Provide relevant, actionable steps for their particular situation
            3. Use evidence-based medical guidelines and current best practices
            4. Be concise and focused on the user's specific needs";
            
            // Use HTTP client for API call instead of SDK clients
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.openai.api_key', 'sk-proj-demo'),
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an emergency medical assistant providing real-time, evidence-based guidance using current medical knowledge.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 600,
                    'temperature' => 0.2
                ],
                'timeout' => 10
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $text = $data['choices'][0]['message']['content'] ?? '';
                return $this->parseFirstAidResponse($text);
            }
        } catch (\Exception $e) {
            Log::error('Fallback AI service error: ' . $e->getMessage());
        }

        // Final fallback - provides specific recommendations based on query
        $symptomsLower = strtolower($symptoms);
        
        if (strpos($symptomsLower, 'chest') !== false || strpos($symptomsLower, 'heart') !== false) {
            $condition = 'Chest Pain / Possible Heart Attack';
            $level = 'critical';
            $action = 'Call emergency services immediately (912)';
            $steps = [
                'Call 912 or local emergency number',
                'Have the person sit down and rest',
                'Give aspirin if available and person is not allergic',
                'Loosen tight clothing',
                'Monitor breathing and consciousness',
                'Be prepared to perform CPR if needed'
            ];
            $emergencyCall = true;
            $signs = ['Chest pressure/pain', 'Shortness of breath', 'Pain in arm/jaw', 'Cold sweat'];
        } elseif (strpos($symptomsLower, 'bleeding') !== false || strpos($symptomsLower, 'bleed') !== false || strpos($symptomsLower, 'blood') !== false) {
            $condition = 'Severe Bleeding';
            $level = 'urgent';
            $action = 'Apply direct pressure with clean cloth';
            $steps = [
                'Apply firm pressure with clean cloth',
                'Elevate injured area above heart',
                'Maintain pressure until bleeding stops',
                'Apply pressure bandage if available',
                'Seek medical attention if bleeding continues'
            ];
            $emergencyCall = false;
            $signs = ['Heavy bleeding', 'Pale skin', 'Rapid pulse', 'Dizziness'];
        } elseif (strpos($symptomsLower, 'choke') !== false || strpos($symptomsLower, 'choking') !== false || strpos($symptomsLower, 'chocking') !== false || strpos($symptomsLower, 'breath') !== false) {
            $condition = (strpos($symptomsLower, 'choke') !== false || strpos($symptomsLower, 'choking') !== false || strpos($symptomsLower, 'chocking') !== false) ? 'Choking / Airway Obstruction' : 'Difficulty Breathing';
            $level = 'critical';
            $action = 'Call emergency services immediately';
            $steps = [
                'Ask if they can speak/cough',
                'Perform Heimlich maneuver if unable to breathe',
                'Call emergency services if unsuccessful',
                'Begin CPR if unconscious'
            ];
            $emergencyCall = true;
            $signs = ['Cannot speak/breathe', 'Blue lips', 'Hands to throat', 'No coughing'];
        } elseif (strpos($symptomsLower, 'burn') !== false) {
            $condition = 'Burns';
            $level = 'urgent';
            $action = 'Cool burn with cool running water';
            $steps = [
                'Cool burn with cool running water for 10-15 minutes',
                'Remove jewelry or tight clothing from burned area',
                'Cover burn with sterile, non-stick dressing',
                'Seek medical attention for severe burns'
            ];
            $emergencyCall = false;
            $signs = ['Large burn area', 'Deep burns', 'Burns on face/hands/genitals', 'Blistering'];
        } elseif (strpos($symptomsLower, 'head') !== false || strpos($symptomsLower, 'fall') !== false) {
            $condition = 'Head Injury';
            $level = 'urgent';
            $action = 'Apply ice to reduce swelling';
            $steps = [
                'Apply ice or cold pack to injured area',
                'Monitor for consciousness changes',
                'Avoid moving person unnecessarily',
                'Seek medical evaluation for head injuries'
            ];
            $emergencyCall = false;
            $signs = ['Headache', 'Dizziness', 'Nausea', 'Vision changes', 'Confusion'];
        } else {
            $condition = 'Medical Assessment Required';
            $level = 'moderate';
            $action = 'Monitor and seek medical advice';
            $steps = [
                'Stay calm and assess the situation',
                'Ensure person is comfortable',
                'Monitor vital signs',
                'Call 912 if condition worsens',
                'Provide basic first aid if trained'
            ];
            $emergencyCall = false;
            $signs = ['Condition worsens', 'Person becomes unconscious', 'Difficulty breathing'];
        }
        
        return [
            'emergency_level' => $level,
            'condition_name' => $condition,
            'immediate_action' => $action,
            'steps' => $steps,
            'emergency_call' => $emergencyCall,
            'warning_signs' => $signs,
            'important_notes' => ['This is not medical advice. Call emergency services for serious conditions.']
        ];
    }

    /**
     * Fallback suggestions when AI is unavailable - uses real-time data
     */
    private function getFallbackSuggestions(string $query): array
    {
        try {
            // Try to use AI for real-time emergency condition suggestions
            $prompt = "Analyze the user's specific emergency query: '{$query}' and suggest 3-5 possible emergency conditions that exactly match what they described.
            
            IMPORTANT: Focus specifically on symptoms/conditions mentioned. If they mention 'chest pain', suggest conditions related to chest pain. If they mention 'bleeding', suggest bleeding-related conditions.
            
            Use current medical knowledge and evidence-based guidelines. 
            Return only specific condition names, one per line, without numbering. Match your suggestions to exactly what the user described.";
            
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.openai.api_key', 'sk-proj-demo'),
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a medical assistant providing real-time emergency condition suggestions based on current medical knowledge.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'max_tokens' => 150,
                    'temperature' => 0.3
                ],
                'timeout' => 8
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                $text = $data['choices'][0]['message']['content'] ?? '';
                $suggestions = array_filter(array_map('trim', explode("\n", $text)));
                return array_slice($suggestions, 0, 5);
            }
        } catch (\Exception $e) {
            Log::error('Fallback suggestions AI service error: ' . $e->getMessage());
        }

        // Final fallback - provides specific suggestions based on query
        $queryLower = strtolower($query);
        
        if (strpos($queryLower, 'chest') !== false || strpos($queryLower, 'heart') !== false) {
            return ['Heart Attack', 'Chest Pain', 'Cardiac Arrest'];
        } elseif (strpos($queryLower, 'bleed') !== false || strpos($queryLower, 'bleeding') !== false || strpos($queryLower, 'blood') !== false) {
            return ['Severe Bleeding', 'Wound Care', 'Hemorrhage'];
        } elseif (strpos($queryLower, 'choke') !== false || strpos($queryLower, 'breath') !== false) {
            return ['Choking', 'Breathing Difficulty', 'Airway Obstruction'];
        } elseif (strpos($queryLower, 'burn') !== false) {
            return ['Burns', 'Thermal Burns', 'Electrical Burns'];
        } elseif (strpos($queryLower, 'head') !== false || strpos($queryLower, 'fall') !== false) {
            return ['Head Injury', 'Concussion', 'Traumatic Brain Injury'];
        } elseif (strpos($queryLower, 'fracture') !== false || strpos($queryLower, 'break') !== false) {
            return ['Fractures', 'Broken Bones', 'Orthopedic Injury'];
        } else {
            return ['Medical Assessment Required', 'Emergency Evaluation', 'Professional Consultation'];
        }
    }

    /**
     * Get available providers information
     */
    public function getAvailableProviders(): array
    {
        return $this->availableProviders;
    }
}
