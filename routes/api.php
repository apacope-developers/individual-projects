<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\FreeAIController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Emergency Conditions API
Route::get('/emergency-conditions', function () {
    $firstAidConfig = config('firstaid');
    
    return response()->json([
        'conditions' => $firstAidConfig['conditions'] ?? [],
        'categories' => $firstAidConfig['categories'] ?? [],
        'severity_levels' => $firstAidConfig['severity_levels'] ?? [],
        'drsabcd' => $firstAidConfig['drsabcd'] ?? [],
        'emergency_contacts' => $firstAidConfig['emergency_contacts'] ?? [],
    ]);
});

// Emergency Categories API
Route::get('/emergency-categories', function () {
    $firstAidConfig = config('firstaid');
    
    return response()->json([
        'categories' => $firstAidConfig['categories'] ?? [],
    ]);
});

// Emergency Conditions by Category
Route::get('/emergency-conditions/category/{category}', function ($category) {
    $firstAidConfig = config('firstaid');
    $conditions = $firstAidConfig['conditions'] ?? [];
    
    $filteredConditions = collect($conditions)->filter(function ($condition) use ($category) {
        return $condition['category'] === $category;
    })->values();
    
    return response()->json([
        'conditions' => $filteredConditions,
        'category' => $category,
    ]);
});

// Search Emergency Conditions
Route::get('/emergency-conditions/search', function (Request $request) {
    $query = $request->get('q', '');
    $firstAidConfig = config('firstaid');
    $conditions = $firstAidConfig['conditions'] ?? [];
    
    if (empty($query)) {
        return response()->json(['conditions' => []]);
    }
    
    $filteredConditions = collect($conditions)->filter(function ($condition) use ($query) {
        $searchText = strtolower($query);
        return (
            str_contains(strtolower($condition['name']), $searchText) ||
            str_contains(strtolower($condition['summary']), $searchText) ||
            str_contains(strtolower(implode(' ', $condition['steps'] ?? [])), $searchText)
        );
    })->values();
    
    return response()->json([
        'conditions' => $filteredConditions,
        'query' => $query,
    ]);
});

// AI-powered first aid recommendations
Route::post('/ai/first-aid', [AIController::class, 'getFirstAidRecommendation']);

// AI-powered emergency suggestions
Route::post('/ai/suggestions', [AIController::class, 'getEmergencySuggestions']);

// Voice-to-text processing
Route::post('/ai/voice-to-text', [AIController::class, 'processVoice']);

// Check available AI providers
Route::get('/ai/providers', [AIController::class, 'getProviders']);

// Free AI-powered first aid recommendations
Route::post('/free-ai/first-aid', [FreeAIController::class, 'getFirstAidRecommendation']);

// Free AI-powered emergency suggestions
Route::post('/free-ai/suggestions', [FreeAIController::class, 'getEmergencySuggestions']);

// Check available free AI providers
Route::get('/free-ai/providers', [FreeAIController::class, 'getProviders']);