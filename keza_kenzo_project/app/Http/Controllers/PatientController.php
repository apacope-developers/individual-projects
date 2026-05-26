<?php

namespace App\Http\Controllers;

use App\Models\CleanMedicine;
use App\Models\CleanPharmacy;
use App\Models\CleanOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $recentOrders = CleanOrder::with('medicine', 'pharmacy')
            ->byUser($user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $stats = [
            'total_orders' => CleanOrder::byUser($user->id)->count(),
            'pending_orders' => CleanOrder::byUser($user->id)->byStatus('pending')->count(),
            'delivered_orders' => CleanOrder::byUser($user->id)->byStatus('delivered')->count(),
        ];

        return view('patient.dashboard', compact('stats', 'recentOrders'));
    }

    public function medicines()
    {
        $medicines = CleanMedicine::active()
            ->with(['inventories' => function($query) {
                $query->with('pharmacy')->where('stock_quantity', '>', 0);
            }])
            ->orderBy('name')
            ->paginate(20);

        return view('patient.medicines.index', compact('medicines'));
    }

    public function showMedicine($id)
    {
        $medicine = CleanMedicine::with(['inventories' => function($query) {
                $query->with('pharmacy')->where('stock_quantity', '>', 0);
            }])
            ->findOrFail($id);

        $pharmacies = CleanPharmacy::whereHas('inventories', function($query) use ($id) {
            $query->where('medicine_id', $id)->where('stock_quantity', '>', 0);
        })->with(['inventories' => function($query) use ($id) {
            $query->where('medicine_id', $id)->where('stock_quantity', '>', 0);
        }])->get();

        return view('patient.medicines.show', compact('medicine', 'pharmacies'));
    }

    public function searchMedicines(Request $request)
    {
        $term = $request->get('term');
        
        if (!$term) {
            return response()->json([]);
        }

        $medicines = CleanMedicine::active()
            ->search($term)
            ->with(['inventories' => function($query) {
                $query->with('pharmacy')->where('stock_quantity', '>', 0);
            }])
            ->take(10)
            ->get(['id', 'name', 'category', 'manufacturer', 'strength', 'image']);

        return response()->json($medicines);
    }

    public function createOrder($id)
    {
        $medicine = CleanMedicine::findOrFail($id);
        
        $pharmacies = CleanPharmacy::whereHas('inventories', function($query) use ($id) {
            $query->where('medicine_id', $id)->where('stock_quantity', '>', 0);
        })->with(['inventories' => function($query) use ($id) {
            $query->where('medicine_id', $id)->where('stock_quantity', '>', 0);
        }])->get();

        return view('patient.orders.create', compact('medicine', 'pharmacies'));
    }

    public function storeOrder(Request $request, $id)
    {
        $request->validate([
            'pharmacy_id' => 'required|exists:clean_pharmacies,id',
            'quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $medicine = CleanMedicine::findOrFail($id);
        $pharmacy = CleanPharmacy::findOrFail($request->pharmacy_id);
        $inventory = Inventory::where('pharmacy_id', $request->pharmacy_id)
            ->where('medicine_id', $id)
            ->firstOrFail();

        if ($inventory->stock_quantity < $request->quantity) {
            return redirect()->back()
                ->with('error', 'Insufficient stock. Available quantity: ' . $inventory->stock_quantity);
        }

        $unitPrice = $inventory->selling_price;
        $totalAmount = $unitPrice * $request->quantity;

        $order = CleanOrder::create([
            'user_id' => Auth::id(),
            'medicine_id' => $id,
            'pharmacy_id' => $request->pharmacy_id,
            'quantity' => $request->quantity,
            'unit_price' => $unitPrice,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'delivery_address' => $request->delivery_address,
            'notes' => $request->notes,
        ]);

        // Update inventory stock
        $inventory->stock_quantity -= $request->quantity;
        $inventory->save();

        return redirect()->route('patient.orders')
            ->with('success', 'Order placed successfully!');
    }

    public function orders()
    {
        $orders = CleanOrder::with('medicine', 'pharmacy')
            ->byUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('patient.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $order = CleanOrder::with('medicine', 'pharmacy', 'user')
            ->byUser(Auth::id())
            ->findOrFail($id);

        return view('patient.orders.show', compact('order'));
    }

    public function cancelOrder($id)
    {
        $order = CleanOrder::byUser(Auth::id())
            ->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cannot cancel order that is already being processed.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore inventory stock
        $inventory = Inventory::where('pharmacy_id', $order->pharmacy_id)
            ->where('medicine_id', $order->medicine_id)
            ->first();
        
        if ($inventory) {
            $inventory->stock_quantity += $order->quantity;
            $inventory->save();
        }

        return redirect()->route('patient.orders')
            ->with('success', 'Order cancelled successfully.');
    }
}
