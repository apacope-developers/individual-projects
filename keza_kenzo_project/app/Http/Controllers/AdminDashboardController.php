<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\Medicine;
use App\Models\Pharmacy;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        // Get comprehensive analytics data
        $analytics = $this->getAnalyticsData();
        
        return view('admin.dashboard', compact('analytics'));
    }

    private function getAnalyticsData()
    {
        $now = Carbon::now();
        
        return [
            'overview' => [
                'total_users' => User::count(),
                'total_orders' => Order::count(),
                'total_medicines' => Medicine::count(),
                'total_pharmacies' => Pharmacy::count(),
                'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
                'orders_today' => Order::whereDate('created_at', $now->toDateString())->count(),
                'orders_this_month' => Order::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count(),
            ],
            
            'recent_orders' => Order::with(['user', 'pharmacy'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(),
                
            'top_medicines' => Order::selectRaw('medicine_name, COUNT(*) as order_count, SUM(total_amount) as revenue')
                ->groupBy('medicine_name')
                ->orderBy('order_count', 'desc')
                ->take(10)
                ->get(),
                
            'top_pharmacies' => Order::selectRaw('pharmacy_name, COUNT(*) as order_count, SUM(total_amount) as revenue')
                ->groupBy('pharmacy_name')
                ->orderBy('order_count', 'desc')
                ->take(10)
                ->get(),
                
            'user_activities' => UserActivity::with('user')
                ->orderBy('created_at', 'desc')
                ->take(20)
                ->get(),
                
            'monthly_stats' => $this->getMonthlyStats(),
            'payment_methods' => $this->getPaymentMethodStats(),
            'order_status' => $this->getOrderStatusStats(),
        ];
    }

    private function getMonthlyStats()
    {
        $stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('F Y');
            $stats[$monthName] = [
                'orders' => Order::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
                'revenue' => Order::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->sum('total_amount'),
                'users' => User::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count(),
            ];
        }
        return $stats;
    }

    private function getPaymentMethodStats()
    {
        return Order::selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as revenue')
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get()
            ->map(function ($stat) {
                return [
                    'method' => $stat->payment_method,
                    'count' => $stat->count,
                    'revenue' => 'RWF ' . number_format($stat->revenue, 0),
                    'percentage' => $this->calculatePercentage($stat->count, Order::where('payment_status', 'paid')->count()),
                ];
            });
    }

    private function getOrderStatusStats()
    {
        return Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function ($stat) {
                return [
                    'status' => $stat->status,
                    'count' => $stat->count,
                    'percentage' => $this->calculatePercentage($stat->count, Order::count()),
                ];
            });
    }

    private function calculatePercentage($count, $total)
    {
        return $total > 0 ? round(($count / $total) * 100, 2) : 0;
    }

    public function getDetailedOrders()
    {
        $orders = Order::with(['user', 'medicine', 'pharmacy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json([
            'success' => true,
            'orders' => $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'medicine_name' => $order->medicine_name,
                    'pharmacy_name' => $order->pharmacy_name,
                    'user_name' => $order->user ? $order->user->name : 'N/A',
                    'user_email' => $order->user ? $order->user->email : 'N/A',
                    'quantity' => $order->quantity,
                    'total_amount' => 'RWF ' . number_format($order->total_amount, 0),
                    'payment_method' => $order->payment_method,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at->format('M d, Y h:i A'),
                    'insurance_provider' => $order->insurance_provider,
                    'insurance_coverage' => $order->insurance_coverage ? 'RWF ' . number_format($order->insurance_coverage, 0) : 'N/A',
                ];
            })
        ]);
    }

    public function getUserActivityDetails()
    {
        $activities = UserActivity::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);
            
        return response()->json([
            'success' => true,
            'activities' => $activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user_name' => $activity->user ? $activity->user->name : 'N/A',
                    'user_email' => $activity->user ? $activity->user->email : 'N/A',
                    'activity_type' => $activity->activity_type,
                    'activity_description' => $activity->activity_description,
                    'search_query' => $activity->search_query,
                    'ip_address' => $activity->ip_address,
                    'created_at' => $activity->created_at->format('M d, Y h:i A'),
                    'time_ago' => $activity->created_at->diffForHumans(),
                ];
            })
        ]);
    }

    public function getRevenueAnalytics()
    {
        $revenue = Order::where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_revenue')
            ->groupBy('DATE(created_at)')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();
            
        return response()->json([
            'success' => true,
            'revenue' => $revenue->map(function ($item) {
                return [
                    'date' => $item->date,
                    'revenue' => 'RWF ' . number_format($item->daily_revenue, 0),
                ];
            })
        ]);
    }
}
