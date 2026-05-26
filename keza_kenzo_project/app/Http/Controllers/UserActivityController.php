<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\UserActivity;
use App\Models\RecentOrder;
use App\Models\RecentSearch;

class UserActivityController extends Controller
{
    public function getRecentPurchases(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please log in to view your recent purchases',
                    'redirect_to' => '/login'
                ], 401);
            }

            // Use the new RecentOrder model for better tracking
            $recentOrders = RecentOrder::forUser(Auth::id())
                ->recent(10)
                ->get();

            // Fallback to Order model if no recent orders exist yet
            if ($recentOrders->isEmpty()) {
                $purchases = Order::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get(['id', 'order_id', 'medicine_name', 'pharmacy_name', 'pharmacy_location', 'quantity', 'total_amount', 'payment_method', 'created_at', 'status', 'payment_status']);

                return response()->json([
                    'success' => true,
                    'purchases' => $purchases->map(function ($order) {
                        return [
                            'id' => $order->id,
                            'order_id' => $order->order_id,
                            'medicine_name' => $order->medicine_name,
                            'pharmacy_name' => $order->pharmacy_name,
                            'pharmacy_location' => $order->pharmacy_location,
                            'quantity' => $order->quantity,
                            'total_amount' => 'RWF ' . number_format($order->total_amount, 0),
                            'payment_method' => $order->payment_method,
                            'status' => $order->status,
                            'payment_status' => $order->payment_status ?? 'pending',
                            'created_at' => $order->created_at->format('M j, Y H:i'),
                            'time_ago' => $order->created_at->diffForHumans()
                        ];
                    })
                ]);
            }

            return response()->json([
                'success' => true,
                'purchases' => $recentOrders->map(function ($order) {
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
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading purchases: ' . $e->getMessage()
            ], 500);
        }
    }

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

            // Use the new RecentSearch model for better tracking
            $recentSearches = RecentSearch::forUser(Auth::id())
                ->recent(10)
                ->get();

            // Fallback to UserActivity model if no recent searches exist yet
            if ($recentSearches->isEmpty()) {
                $searches = UserActivity::where('user_id', Auth::id())
                    ->where('activity_type', 'medicine_search')
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get(['id', 'search_query', 'created_at']);

                return response()->json([
                    'success' => true,
                    'searches' => $searches->map(function ($search) {
                        return [
                            'id' => $search->id,
                            'search_query' => $search->search_query,
                            'created_at' => $search->created_at->format('M j, Y H:i'),
                            'time_ago' => $search->created_at->diffForHumans()
                        ];
                    })
                ]);
            }

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

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

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
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();

        RecentOrder::forUser($user->id)->delete();
        RecentSearch::forUser($user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recent activity cleared successfully',
        ]);
    }

    public function getUserActivitySummary(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        
        $recentOrders = RecentOrder::forUser($user->id)->recent(5)->get();
        $recentSearches = RecentSearch::forUser($user->id)->recent(5)->get();
        $totalPurchases = $user->orders()->where('payment_status', 'paid')->count();
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total_amount');

        return response()->json([
            'success' => true,
            'summary' => [
                'total_purchases' => $totalPurchases,
                'total_spent' => 'RWF ' . number_format($totalSpent, 0),
                'recent_purchases' => $recentOrders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'order_id' => $order->order_id,
                        'medicine_name' => $order->medicine_name,
                        'pharmacy_name' => $order->pharmacy_name,
                        'quantity' => $order->quantity,
                        'total_amount' => $order->formatted_amount,
                        'payment_method' => ucfirst($order->payment_method),
                        'created_at' => $order->created_at->format('M j, Y H:i'),
                        'time_ago' => $order->time_ago
                    ];
                }),
                'recent_searches' => $recentSearches->map(function ($search) {
                    return [
                        'id' => $search->id,
                        'search_query' => $search->search_query,
                        'results_count' => $search->results_count,
                        'created_at' => $search->created_at->format('M j, Y H:i'),
                        'time_ago' => $search->time_ago
                    ];
                })
            ]
        ]);
    }
}
