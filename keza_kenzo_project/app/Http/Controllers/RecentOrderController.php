<?php

namespace App\Http\Controllers;

use App\Models\RecentOrder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RecentOrderController extends Controller
{
    /**
     * Display the recent orders page.
     */
    public function index()
    {
        return view('recent-orders.index');
    }

    /**
     * Get recent orders for the authenticated user.
     */
    public function getRecentOrders(): JsonResponse
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Please log in to view your recent orders',
                    'redirect_to' => '/login'
                ], 401);
            }

            $recentOrders = RecentOrder::forUser(Auth::id())
                ->with('order.medicine') // Load related medicine
                ->recent(20)
                ->get();

            return response()->json([
                'success' => true,
                'orders' => $recentOrders->map(function ($order) {
                    // Get medicine image from the related order or use placeholder
                    $medicineImage = null;
                    if ($order->order && $order->order->medicine) {
                        $medicineImage = $order->order->medicine->image_url;
                    } else {
                        // Generate placeholder based on medicine name
                        $medicineImage = 'https://via.placeholder.com/100x100/0ea5e9/ffffff?text=' . urlencode(substr($order->medicine_name, 0, 3));
                    }
                    
                    return [
                        'id' => $order->id,
                        'order_id' => $order->order_id,
                        'original_order_id' => $order->order_id, // For tracking and receipt
                        'medicine_name' => $order->medicine_name,
                        'medicine_image' => $medicineImage,
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
                'message' => 'Error loading orders: ' . $e->getMessage()
            ], 500);
        }
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
     * Clear all recent orders for the authenticated user.
     */
    public function clearRecentOrders(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $user = Auth::user();
        RecentOrder::forUser($user->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recent orders cleared successfully',
        ]);
    }

    /**
     * Delete a specific recent order.
     */
    public function deleteRecentOrder($id): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $recentOrder = RecentOrder::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$recentOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Recent order not found'
            ], 404);
        }

        $recentOrder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recent order deleted successfully',
        ]);
    }
}
