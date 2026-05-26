<?php

namespace App\Http\Controllers;

use App\Models\CleanMedicine;
use App\Models\CleanPharmacy;
use App\Models\Inventory;
use App\Models\CleanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CleanAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_medicines' => CleanMedicine::count(),
            'total_pharmacies' => CleanPharmacy::count(),
            'total_orders' => CleanOrder::count(),
            'total_inventory' => Inventory::count(),
            'low_stock_items' => Inventory::lowStock()->count(),
            'out_of_stock_items' => Inventory::outOfStock()->count(),
            'pending_orders' => CleanOrder::byStatus('pending')->count(),
            'delivered_orders' => CleanOrder::byStatus('delivered')->count(),
            'total_revenue' => CleanOrder::where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recentOrders = CleanOrder::with(['user', 'medicine', 'pharmacy'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $lowStockItems = Inventory::with(['medicine', 'pharmacy'])
            ->lowStock()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'lowStockItems'));
    }

    // Medicine Management
    public function medicines()
    {
        $medicines = CleanMedicine::withCount('inventories')
            ->orderBy('name')
            ->paginate(15);

        return view('admin.medicines.index', compact('medicines'));
    }

    public function createMedicine()
    {
        return view('admin.medicines.create');
    }

    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clean_medicines,name',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'dosage_form' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('medicines', $imageName, 'public');
            $data['image'] = $imageName;
        }

        $medicine = CleanMedicine::create($data);

        return redirect()->route('admin.medicines')
            ->with('success', 'Medicine created successfully.');
    }

    public function editMedicine($id)
    {
        $medicine = CleanMedicine::findOrFail($id);
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function updateMedicine(Request $request, $id)
    {
        $medicine = CleanMedicine::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:clean_medicines,name,' . $id,
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'strength' => 'nullable|string|max:100',
            'dosage_form' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'requires_prescription' => 'boolean',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($medicine->image) {
                Storage::disk('public')->delete('medicines/' . $medicine->image);
            }
            
            $image = $request->file('image');
            $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('medicines', $imageName, 'public');
            $data['image'] = $imageName;
        } else {
            unset($data['image']);
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines')
            ->with('success', 'Medicine updated successfully.');
    }

    public function deleteMedicine($id)
    {
        $medicine = CleanMedicine::findOrFail($id);
        
        if ($medicine->image) {
            Storage::disk('public')->delete('medicines/' . $medicine->image);
        }
        
        $medicine->delete();

        return redirect()->route('admin.medicines')
            ->with('success', 'Medicine deleted successfully.');
    }

    // Pharmacy Management
    public function pharmacies()
    {
        $pharmacies = CleanPharmacy::withCount(['inventories', 'orders'])
            ->orderBy('name')
            ->paginate(15);

        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function createPharmacy()
    {
        return view('admin.pharmacies.create');
    }

    public function storePharmacy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:clean_pharmacies,name',
            'email' => 'nullable|email|unique:clean_pharmacies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:255|unique:clean_pharmacies,license_number',
            'is_active' => 'boolean'
        ]);

        CleanPharmacy::create($request->all());

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacy created successfully.');
    }

    public function editPharmacy($id)
    {
        $pharmacy = CleanPharmacy::findOrFail($id);
        return view('admin.pharmacies.edit', compact('pharmacy'));
    }

    public function updatePharmacy(Request $request, $id)
    {
        $pharmacy = CleanPharmacy::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:clean_pharmacies,name,' . $id,
            'email' => 'nullable|email|unique:clean_pharmacies,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:255|unique:clean_pharmacies,license_number,' . $id,
            'is_active' => 'boolean'
        ]);

        $pharmacy->update($request->all());

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacy updated successfully.');
    }

    public function deletePharmacy($id)
    {
        $pharmacy = CleanPharmacy::findOrFail($id);
        $pharmacy->delete();

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacy deleted successfully.');
    }

    // Order Management
    public function orders()
    {
        $orders = CleanOrder::with(['user', 'medicine', 'pharmacy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = CleanOrder::with(['user', 'medicine', 'pharmacy'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order = CleanOrder::findOrFail($id);
        $order->update($request->only(['status', 'payment_status']));

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }

    // Inventory Management
    public function inventory()
    {
        $inventory = Inventory::with(['medicine', 'pharmacy'])
            ->orderBy('pharmacy_id')
            ->orderBy('medicine_name')
            ->paginate(20);

        return view('admin.inventory.index', compact('inventory'));
    }

    public function lowStock()
    {
        $lowStock = Inventory::with(['medicine', 'pharmacy'])
            ->lowStock()
            ->orderBy('stock_quantity')
            ->paginate(20);

        return view('admin.inventory.low-stock', compact('lowStock'));
    }
}
