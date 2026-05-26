<?php

namespace App\Http\Controllers;

use App\Models\RecentOrder;
use App\Models\RecentSearch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RecentActivityController extends Controller
{
    /**
     * Get recent purchases for the authenticated user.
     */
    public function getRecentPurchases(): JsonResponse
    {
        $user = Auth::user();
        
        $recentOrders = RecentOrder::forUser($user->id)
            ->recent(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'medicine_name' => $order->medicine_name,
                    'pharmacy_name' => $order->pharmacy_name,
                    'pharmacy_location' => $order->pharmacy_location,
                    'quantity' => $order->quantity,
                    'total_amount' => $order->formatted_amount,
                    'payment_method' => ucfirst($order->payment_method),
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('M j, Y H:i'),
                    'time_ago' => $order->time_ago,
                ];
            });

        return response()->json([
            'success' => true,
            'purchases' => $recentOrders,
        ]);
    }

    /**
     * Get recent searches for the authenticated user.
     */
    public function getRecentSearches(): JsonResponse
    {
        $user = Auth::user();
        
        $recentSearches = RecentSearch::forUser($user->id)
            ->recent(10)
            ->get()
            ->map(function ($search) {
                return [
                    'id' => $search->id,
                    'search_query' => $search->search_query,
                    'search_filters' => $search->search_filters,
                    'results_count' => $search->results_count,
                    'created_at' => $search->created_at->format('M j, Y H:i'),
                    'time_ago' => $search->time_ago,
                ];
            });

        return response()->json([
            'success' => true,
            'searches' => $recentSearches,
        ]);
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
     * Save a recent order automatically.
     */
    public function saveRecentOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'medicine_name' => 'required|string|max:255',
            'pharmacy_name' => 'required|string|max:255',
            'pharmacy_location' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:50',
            'status' => 'required|string|max:50',
        ]);

        $user = Auth::user();

        // Delete old orders (keep only last 20)
        RecentOrder::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->offset(20)
            ->delete();

        // Create new recent order
        $recentOrder = RecentOrder::create([
            'user_id' => $user->id,
            'order_id' => $request->order_id,
            'medicine_name' => $request->medicine_name,
            'pharmacy_name' => $request->pharmacy_name,
            'pharmacy_location' => $request->pharmacy_location,
            'quantity' => $request->quantity,
            'total_amount' => $request->total_amount,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Order saved successfully',
            'recent_order_id' => $recentOrder->id,
        ]);
    }

    /**
     * Clear all recent activity for the authenticated user.
     */
    public function clearRecentActivity(): JsonResponse
    {
        $user = Auth::user();

        RecentOrder::forUser($user->id)->delete();
        RecentSearch::forUser($user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recent activity cleared successfully',
        ]);
    }
}
