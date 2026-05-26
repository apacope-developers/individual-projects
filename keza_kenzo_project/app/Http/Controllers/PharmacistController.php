<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Availability;
use App\Models\Order;
use App\Models\DailyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacistController extends Controller
{
    public function getPharmacy()
    {
        $firstPharmacy = Pharmacy::first();
        if (!$firstPharmacy) {
            $firstPharmacy = Pharmacy::create([
                'name' => 'Kigali Central Pharmacy',
                'location' => 'Kigali (Nyabugogo)',
            ]);
        }
        return $firstPharmacy;
    }

    public function dashboard()
    {
        $pharmacy = $this->getPharmacy();

        $totalMedicines = Medicine::count();
        $availabilities = Availability::where('pharmacy_id', $pharmacy->id)->get();

        $inStock = $availabilities->where('stock', '>', 5)->count();
        $lowStock = $availabilities->where('stock', '>', 0)->where('stock', '<=', 5)->count();
        $outOfStock = $availabilities->where('stock', 0)->count();

        $lowStockItems = $availabilities->where('stock', '>', 0)->where('stock', '<=', 5)
            ->with('medicine')
            ->latest()
            ->take(10)
            ->get();

        $stats = compact('totalMedicines', 'inStock', 'lowStock', 'outOfStock');

        return view('pharmacist.dashboard', compact('pharmacy', 'stats', 'lowStockItems'));
    }

    public function medicines(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $query = Availability::with('medicine')
            ->where('pharmacy_id', $pharmacy->id);

        if ($request->filled('stock_filter')) {
            $filter = $request->stock_filter;
            if ($filter === 'instock') {
                $query->where('stock', '>', 5);
            } elseif ($filter === 'lowstock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            } elseif ($filter === 'outofstock') {
                $query->where('stock', 0);
            }
        }

        $medicines = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('pharmacist.medicines', compact('pharmacy', 'medicines'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id'   => 'required|integer|exists:medicines,id',
            'stock'         => 'required|integer|min:0',
            'price'         => 'required|numeric|min:0',
        ]);

        $pharmacy = $this->getPharmacy();

        $existing = Availability::where('pharmacy_id', $pharmacy->id)
            ->where('medicine_id', $request->medicine_id)
            ->first();

        if ($existing) {
            $existing->update([
                'stock' => $request->stock,
                'price' => $request->price,
            ]);
            return back()->with('success', 'Stock and price updated successfully!');
        }

        Availability::create([
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $request->medicine_id,
            'stock'       => $request->stock,
            'price'       => $request->price,
        ]);

        return back()->with('success', 'Medicine added to inventory successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        $pharmacy = $this->getPharmacy();
        $availability = Availability::where('id', $id)
            ->where('pharmacy_id', $pharmacy->id)
            ->firstOrFail();

        $availability->update([
            'stock' => $request->stock,
            'price' => $request->price,
        ]);

        return back()->with('success', 'Stock updated successfully!');
    }

    public function destroy($id)
    {
        $pharmacy = $this->getPharmacy();
        $availability = Availability::where('id', $id)
            ->where('pharmacy_id', $pharmacy->id)
            ->firstOrFail();

        $availability->delete();

        return back()->with('success', 'Medicine removed from inventory successfully!');
    }

    public function search(Request $request)
    {
        $q = trim($request->get('search', ''));

        if (strlen($q) < 2) {
            return [];
        }

        return Medicine::where('name', 'like', "%{$q}%")
            ->select('id', 'name')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function orders(Request $request)
    {
        $pharmacy = $this->getPharmacy();

        $statusFilter = $request->get('status', 'all');

        $query = Order::with(['user', 'medicine'])
            ->where('pharmacy_id', $pharmacy->id);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        $orderStats = [
            'pending'    => Order::where('pharmacy_id', $pharmacy->id)->where('status', 'pending')->count(),
            'confirmed'  => Order::where('pharmacy_id', $pharmacy->id)->where('status', 'confirmed')->count(),
            'processing' => Order::where('pharmacy_id', $pharmacy->id)->where('status', 'processing')->count(),
            'shipped'    => Order::where('pharmacy_id', $pharmacy->id)->where('status', 'shipped')->count(),
            'delivered'  => Order::where('pharmacy_id', $pharmacy->id)->where('status', 'delivered')->count(),
            'cancelled'  => Order::where('pharmacy_id', $pharmacy->id)->where('status', 'cancelled')->count(),
        ];

        return view('pharmacist.orders', compact('pharmacy', 'orders', 'statusFilter', 'orderStats'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $pharmacy = $this->getPharmacy();
        $order = Order::where('id', $id)
            ->where('pharmacy_id', $pharmacy->id)
            ->firstOrFail();

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated to ' . $request->status);
    }

    public function reports(Request $request)
    {
        $pharmacy = $this->getPharmacy();
        $period = $request->get('period', 'daily');

        $reportQuery = DailyReport::where('pharmacy_id', $pharmacy->id);

        if ($period === 'daily') {
            $reportQuery->whereDate('created_at', now()->toDateString());
        } elseif ($period === 'weekly') {
            $reportQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($period === 'monthly') {
            $reportQuery->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }

        $reports = $reportQuery->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total_reports'   => DailyReport::where('pharmacy_id', $pharmacy->id)->count(),
            'daily_reports'   => DailyReport::where('pharmacy_id', $pharmacy->id)
                ->whereDate('created_at', now()->toDateString())->count(),
            'total_issues'    => DailyReport::where('pharmacy_id', $pharmacy->id)->sum('issues_reported'),
            'pending_issues'  => DailyReport::where('pharmacy_id', $pharmacy->id)
                ->where('issues_reported', '>', 0)->count(),
        ];

        return view('pharmacist.reports', compact('pharmacy', 'reports', 'period', 'stats'));
    }

    public function storeDailyWork(Request $request)
    {
        $request->validate([
            'work_date'         => 'required|date',
            'medicines_restocked'=> 'nullable|integer|min:0',
            'orders_fulfilled'  => 'nullable|integer|min:0',
            'customers_served'  => 'nullable|integer|min:0',
            'issues_reported'   => 'nullable|integer|min:0',
            'notes'             => 'nullable|string|max:2000',
        ]);

        $pharmacy = $this->getPharmacy();

        DailyReport::updateOrCreate(
            [
                'pharmacy_id' => $pharmacy->id,
                'work_date'   => $request->work_date,
            ],
            [
                'medicines_restocked' => $request->medicines_restocked ?? 0,
                'orders_fulfilled'    => $request->orders_fulfilled ?? 0,
                'customers_served'    => $request->customers_served ?? 0,
                'issues_reported'     => $request->issues_reported ?? 0,
                'notes'               => $request->notes ?? '',
            ]
        );

        return back()->with('success', 'Daily work report saved successfully!');
    }
}
