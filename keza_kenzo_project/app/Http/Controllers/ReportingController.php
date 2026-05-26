<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Availability;
use App\Models\UserActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());
        $groupBy = $request->input('group_by', 'day');

        $query = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid');

        $data = $this->groupByPeriod($query, $groupBy, [
            'revenue' => DB::raw('SUM(total_amount)'),
            'orders' => DB::raw('COUNT(*)'),
            'avg_order_value' => DB::raw('AVG(total_amount)')
        ]);

        return response()->json([
            'data' => $data,
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'avg_order_value' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')->avg('total_amount')
        ]);
    }

    public function inventoryReport()
    {
        $data = [
            'total_medicines' => Medicine::count(),
            'total_pharmacies' => Pharmacy::count(),
            'total_stock_records' => Availability::count(),
            'low_stock_items' => Availability::where('stock', '<', 5)->count(),
            'out_of_stock_items' => Availability::where('stock', 0)->count(),
            'top_medicines_by_stock' => Availability::with('medicine')
                ->orderBy('stock', 'desc')
                ->take(10)
                ->get(),
            'pharmacies_with_low_stock' => Pharmacy::whereHas('availabilities', function($query) {
                $query->where('stock', '<', 5);
            })->withCount(['availabilities' => function($query) {
                $query->where('stock', '<', 5);
            }])->get(),
            'stock_value_by_pharmacy' => Pharmacy::with(['availabilities' => function($query) {
                $query->with('medicine');
            }])->get()->map(function($pharmacy) {
                return [
                    'pharmacy' => $pharmacy->name,
                    'total_items' => $pharmacy->availabilities->count(),
                    'total_stock' => $pharmacy->availabilities->sum('stock'),
                    'total_value' => $pharmacy->availabilities->sum(function($availability) {
                        return $availability->stock * $availability->price;
                    })
                ];
            })
        ];

        return response()->json($data);
    }

    public function userActivityReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        $data = [
            'total_users' => User::count(),
            'active_users' => User::whereHas('activity', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count(),
            'new_users' => User::whereBetween('created_at', [$startDate, $endDate])->count(),
            'user_registrations_by_day' => User::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            'top_activities' => UserActivity::selectRaw('activity_type, COUNT(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('activity_type')
                ->orderBy('count', 'desc')
                ->get(),
            'user_engagement' => [
                'searches_per_user' => UserActivity::where('activity_type', 'medicine_search')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count() / max(User::whereHas('activity', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    })->count(), 1),
                'orders_per_user' => Order::whereBetween('created_at', [$startDate, $endDate])
                    ->count() / max(User::whereHas('orders', function($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    })->count(), 1)
            ]
        ];

        return response()->json($data);
    }

    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        $data = [
            'total_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'paid_orders' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')->count(),
            'pending_orders' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'pending')->count(),
            'revenue_by_payment_method' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->selectRaw('payment_method, SUM(total_amount) as revenue, COUNT(*) as count')
                ->groupBy('payment_method')
                ->get(),
            'revenue_by_pharmacy' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->selectRaw('pharmacy_name, SUM(total_amount) as revenue, COUNT(*) as orders')
                ->groupBy('pharmacy_name')
                ->orderBy('revenue', 'desc')
                ->take(10)
                ->get(),
            'top_medicines_by_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->selectRaw('medicine_name, SUM(total_amount) as revenue, COUNT(*) as orders')
                ->groupBy('medicine_name')
                ->orderBy('revenue', 'desc')
                ->take(10)
                ->get(),
            'daily_revenue' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid')
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        return response()->json($data);
    }

    public function exportReport(Request $request)
    {
        $type = $request->input('type');
        $format = $request->input('format', 'csv');
        $startDate = $request->input('start_date', now()->subDays(30));
        $endDate = $request->input('end_date', now());

        switch ($type) {
            case 'sales':
                $data = Order::whereBetween('created_at', [$startDate, $endDate])
                    ->with(['user', 'pharmacy'])
                    ->get()
                    ->map(function($order) {
                        return [
                            'Order ID' => $order->order_id,
                            'Customer' => $order->user ? $order->user->name : 'N/A',
                            'Medicine' => $order->medicine_name,
                            'Pharmacy' => $order->pharmacy_name,
                            'Quantity' => $order->quantity,
                            'Total Amount' => $order->total_amount,
                            'Payment Method' => $order->payment_method,
                            'Status' => $order->status,
                            'Date' => $order->created_at->format('Y-m-d H:i:s')
                        ];
                    });
                break;

            case 'inventory':
                $data = Availability::with(['medicine', 'pharmacy'])
                    ->get()
                    ->map(function($availability) {
                        return [
                            'Medicine' => $availability->medicine->name,
                            'Pharmacy' => $availability->pharmacy->name,
                            'Stock' => $availability->stock,
                            'Price' => $availability->price,
                            'Total Value' => $availability->stock * $availability->price
                        ];
                    });
                break;

            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        if ($format === 'csv') {
            return $this->exportCsv($data, $type . '_report_' . now()->format('Y-m-d') . '.csv');
        }

        return response()->json($data);
    }

    private function groupByPeriod($query, $period, $selects)
    {
        switch ($period) {
            case 'hour':
                return $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m-%d %H:00:00") as period, ' . implode(', ', array_keys($selects)))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            case 'day':
                return $query->selectRaw('DATE(created_at) as period, ' . implode(', ', array_keys($selects)))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            case 'week':
                return $query->selectRaw('YEARWEEK(created_at) as period, ' . implode(', ', array_keys($selects)))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            case 'month':
                return $query->selectRaw('DATE_FORMAT(created_at, "%Y-%m-01") as period, ' . implode(', ', array_keys($selects)))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
            default:
                return $query->selectRaw('DATE(created_at) as period, ' . implode(', ', array_keys($selects)))
                    ->groupBy('period')
                    ->orderBy('period')
                    ->get();
        }
    }

    private function exportCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            if ($data->isNotEmpty()) {
                fputcsv($file, array_keys($data->first()));
                
                foreach ($data as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
