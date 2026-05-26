<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Availability;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkingAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'medicines' => Medicine::count(),
            'pharmacies' => Pharmacy::count(),
            'availabilities' => Availability::count(),
            'lowStock' => Availability::where('stock', '<', 5)->count(),
        ];

        $analytics = [
            'overview' => [
                'total_users' => \App\Models\User::count(),
                'total_orders' => \App\Models\Order::count(),
                'total_medicines' => Medicine::count(),
                'total_pharmacies' => Pharmacy::count(),
                'total_revenue' => \App\Models\Order::where('payment_status', 'paid')->sum('total_amount'),
                'orders_today' => \App\Models\Order::whereDate('created_at', now()->toDateString())->count(),
                'orders_this_month' => \App\Models\Order::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ],
            'recent_orders' => \App\Models\Order::with(['user', 'pharmacy'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('admin.dashboard', compact('stats', 'analytics'));
    }

    // Medicine CRUD Operations
    public function medicines()
    {
        $medicines = Medicine::orderBy('name')->paginate(20);
        return view('admin.medicines.index', compact('medicines'));
    }

    public function createMedicine()
    {
        return view('admin.medicines.create');
    }

    public function storeMedicine(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name',
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

        Medicine::create($data);

        return redirect()->route('admin.medicines')
            ->with('success', 'Medicine created successfully.');
    }

    public function editMedicine($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function updateMedicine(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:medicines,name,' . $id,
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
        $medicine = Medicine::findOrFail($id);
        
        if ($medicine->image) {
            Storage::disk('public')->delete('medicines/' . $medicine->image);
        }
        
        $medicine->delete();

        return redirect()->route('admin.medicines')
            ->with('success', 'Medicine deleted successfully.');
    }

    // Pharmacy CRUD Operations
    public function pharmacies()
    {
        $pharmacies = Pharmacy::orderBy('name')->paginate(20);
        return view('admin.pharmacies.index', compact('pharmacies'));
    }

    public function createPharmacy()
    {
        return view('admin.pharmacies.create');
    }

    public function storePharmacy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:pharmacies,name',
            'email' => 'nullable|email|unique:pharmacies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:255|unique:pharmacies,license_number',
            'is_active' => 'boolean'
        ]);

        Pharmacy::create($request->all());

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacy created successfully.');
    }

    public function editPharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        return view('admin.pharmacies.edit', compact('pharmacy'));
    }

    public function updatePharmacy(Request $request, $id)
    {
        $pharmacy = Pharmacy::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:pharmacies,name,' . $id,
            'email' => 'nullable|email|unique:pharmacies,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:255|unique:pharmacies,license_number,' . $id,
            'is_active' => 'boolean'
        ]);

        $pharmacy->update($request->all());

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacy updated successfully.');
    }

    public function deletePharmacy($id)
    {
        $pharmacy = Pharmacy::findOrFail($id);
        $pharmacy->delete();

        return redirect()->route('admin.pharmacies')
            ->with('success', 'Pharmacy deleted successfully.');
    }

    // Order Management
    public function orders()
    {
        $orders = Order::with(['user', 'medicine', 'pharmacy'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = Order::with(['user', 'medicine', 'pharmacy'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order = Order::findOrFail($id);
        $order->update($request->only(['status', 'payment_status']));

        return redirect()->back()
            ->with('success', 'Order status updated successfully.');
    }
}
