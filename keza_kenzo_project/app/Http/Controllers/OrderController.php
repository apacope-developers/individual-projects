<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Order;
use App\Models\Pharmacy;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Medicine $medicine)
    {
        if (!session('user_id')) {
            return redirect('/login')->with('error', 'Please login to place an order');
        }

        $pharmacy = Pharmacy::first();
        $availability = Availability::where('medicine_id', $medicine->id)
            ->where('pharmacy_id', $pharmacy->id)
            ->first();

        if (!$availability || $availability->stock < 1) {
            return back()->with('error', 'Medicine is out of stock');
        }

        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'user_id' => session('user_id'),
            'medicine_id' => $medicine->id,
            'pharmacy_id' => $pharmacy->id,
            'quantity' => 1,
            'unit_price' => $medicine->price,
            'total_amount' => $medicine->price,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return redirect('/dashboard')->with('success', 'Order placed successfully! Order #' . $order->order_number);
    }

    public function pay(Order $order)
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        if ($order->user_id !== session('user_id')) {
            return back()->with('error', 'Unauthorized access to this order');
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'payment_method' => 'cash_on_delivery',
        ]);

        return back()->with('success', 'Payment completed for order #' . $order->order_number);
    }

    public function index()
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        $orders = Order::with(['medicine', 'pharmacy'])
            ->where('user_id', session('user_id'))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('orders.index', compact('orders'));
    }
}