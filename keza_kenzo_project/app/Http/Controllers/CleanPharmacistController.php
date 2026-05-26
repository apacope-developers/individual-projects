<?php

namespace App\Http\Controllers;

use App\Models\CleanMedicine;
use App\Models\CleanPharmacy;
use App\Models\Inventory;
use App\Models\CleanOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CleanPharmacistController extends Controller
{
    public function dashboard()
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $stats = [
            'total_medicines' => Inventory::where('pharmacy_id', $pharmacy->id)->count(),
            'total_stock' => Inventory::where('pharmacy_id', $pharmacy->id)->sum('stock_quantity'),
            'low_stock_items' => Inventory::where('pharmacy_id', $pharmacy->id)->lowStock()->count(),
            'out_of_stock_items' => Inventory::where('pharmacy_id', $pharmacy->id)->outOfStock()->count(),
            'expiring_soon' => Inventory::where('pharmacy_id', $pharmacy->id)->expiringSoon(30)->count(),
            'recent_orders' => CleanOrder::where('pharmacy_id', $pharmacy->id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            'total_revenue' => CleanOrder::where('pharmacy_id', $pharmacy->id)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
        ];

        $lowStockItems = Inventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->lowStock()
            ->take(10)
            ->get();

        $recentOrders = CleanOrder::with('user', 'medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('pharmacist.dashboard', compact('stats', 'lowStockItems', 'recentOrders', 'pharmacy'));
    }

    // Inventory Management
    public function inventory()
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $inventory = Inventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('medicine_name')
            ->paginate(20);

        return view('pharmacist.inventory.index', compact('inventory', 'pharmacy'));
    }

    public function addMedicine()
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $medicines = CleanMedicine::whereDoesntHave('inventories', function ($query) use ($pharmacy) {
            $query->where('pharmacy_id', $pharmacy->id);
        })->get();

        return view('pharmacist.inventory.add', compact('medicines', 'pharmacy'));
    }

    public function storeMedicine(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $request->validate([
            'medicine_id' => 'required|exists:clean_medicines,id',
            'stock_quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:1',
            'max_stock' => 'required|integer|min:1',
            'batch_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date|after:today',
            'storage_location' => 'nullable|string|max:255'
        ]);

        // Check if medicine already exists in pharmacy
        $existing = Inventory::where('pharmacy_id', $pharmacy->id)
            ->where('medicine_id', $request->medicine_id)
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'This medicine already exists in your inventory.');
        }

        Inventory::create([
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $request->medicine_id,
            'stock_quantity' => $request->stock_quantity,
            'selling_price' => $request->selling_price,
            'reorder_level' => $request->reorder_level,
            'max_stock' => $request->max_stock,
            'batch_number' => $request->batch_number,
            'expiry_date' => $request->expiry_date,
            'storage_location' => $request->storage_location,
        ]);

        return redirect()->route('pharmacist.inventory')
            ->with('success', 'Medicine added to inventory successfully.');
    }

    public function editInventory($id)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $inventory = Inventory::with('medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->findOrFail($id);

        return view('pharmacist.inventory.edit', compact('inventory', 'pharmacy'));
    }

    public function updateInventory(Request $request, $id)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $inventory = Inventory::where('pharmacy_id', $pharmacy->id)
            ->findOrFail($id);

        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:1',
            'max_stock' => 'required|integer|min:1',
            'batch_number' => 'nullable|string|max:255',
            'expiry_date' => 'nullable|date',
            'storage_location' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);

        $inventory->update($request->all());

        return redirect()->route('pharmacist.inventory')
            ->with('success', 'Inventory updated successfully.');
    }

    public function deleteInventory($id)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $inventory = Inventory::where('pharmacy_id', $pharmacy->id)
            ->findOrFail($id);

        $inventory->delete();

        return redirect()->route('pharmacist.inventory')
            ->with('success', 'Medicine removed from inventory successfully.');
    }

    // Order Management
    public function orders()
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $orders = CleanOrder::with('user', 'medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pharmacist.orders.index', compact('orders', 'pharmacy'));
    }

    public function showOrder($id)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $order = CleanOrder::with('user', 'medicine')
            ->where('pharmacy_id', $pharmacy->id)
            ->findOrFail($id);

        return view('pharmacist.orders.show', compact('order', 'pharmacy'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $order = CleanOrder::where('pharmacy_id', $pharmacy->id)
            ->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }

    // Search functionality
    public function searchMedicines(Request $request)
    {
        $pharmacy = Auth::user()->pharmacy;
        
        if (!$pharmacy) {
            abort(403, 'No pharmacy assigned to your account');
        }

        $term = $request->get('term');
        
        if (!$term) {
            return response()->json([]);
        }

        $medicines = CleanMedicine::search($term)
            ->whereDoesntHave('inventories', function ($query) use ($pharmacy) {
                $query->where('pharmacy_id', $pharmacy->id);
            })
            ->take(10)
            ->get(['id', 'name', 'category', 'manufacturer', 'strength', 'image']);

        return response()->json($medicines);
    }
}
