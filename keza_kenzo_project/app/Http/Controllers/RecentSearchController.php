<?php

namespace App\Http\Controllers;

use App\Models\RecentSearch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RecentSearchController extends Controller
{
    /**
     * Display the recent searches page.
     */
    public function index()
    {
        return view('recent-searches.index');
    }

    /**
     * Get recent searches for the authenticated user.
     */
    public function getRecentSearches(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please log in to view your recent searches',
                    'redirect_to' => '/login'
                ], 401);
            }

            $recentSearches = RecentSearch::forUser(Auth::id())
                ->recent(20)
                ->get();

            return response()->json([
                'success' => true,
                'searches' => $recentSearches->map(function ($search) {
                    return [
                        'id' => $search->id,
                        'search_query' => $search->search_query,
                        'search_filters' => $search->search_filters,
                        'results_count' => $search->results_count,
                        'created_at' => $search->created_at->format('M j, Y H:i'),
                        'time_ago' => $search->time_ago,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading searches: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save a recent search automatically.
     */
    public function saveRecentSearch(Request $request): JsonResponse
    {
        $request->validate([
            'search_query' => 'required|string|max:255',
            'search_filters' => 'nullable|array',
            'results_count' => 'nullable|integer|min:0',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        // Delete old searches (keep only last 20)
        RecentSearch::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->offset(20)
            ->delete();

        // Create new recent search
        $recentSearch = RecentSearch::create([
            'user_id' => $user->id,
            'search_query' => $request->search_query,
            'search_filters' => $request->search_filters,
            'results_count' => $request->results_count ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Search saved successfully',
            'search_id' => $recentSearch->id,
        ]);
    }

    /**
     * Clear all recent searches for the authenticated user.
     */
    public function clearRecentSearches(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        RecentSearch::forUser($user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recent searches cleared successfully',
        ]);
    }

    /**
     * Delete a specific recent search.
     */
    public function deleteRecentSearch($id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $recentSearch = RecentSearch::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$recentSearch) {
            return response()->json([
                'success' => false,
                'message' => 'Recent search not found'
            ], 404);
        }

        $recentSearch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recent search deleted successfully',
        ]);
    }

    /**
     * Search again with the same query.
     */
    public function searchAgain(Request $request): JsonResponse
    {
        $request->validate([
            'search_query' => 'required|string|max:255',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $searchQuery = $request->search_query;
        
        // Redirect to medicines search with the query
        return response()->json([
            'success' => true,
            'redirect_url' => "/medicines?search=" . urlencode($searchQuery)
        ]);
    }
}
