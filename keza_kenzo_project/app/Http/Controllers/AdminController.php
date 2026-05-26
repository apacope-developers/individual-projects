<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            "medicines" => Medicine::count(),
            "pharmacies" => Pharmacy::count(),
            "orders" => Order::count(),
            "users" => \App\Models\User::count(),
        ];

        return view("admin.dashboard", compact("stats"));
    }

    public function medicines()
    {
        $medicines = Medicine::paginate(20);
        return view("admin.medicines", compact("medicines"));
    }

    public function pharmacies()
    {
        $pharmacies = Pharmacy::paginate(20);
        return view("admin.pharmacies", compact("pharmacies"));
    }

    public function orders()
    {
        $orders = Order::with(["user", "medicine", "pharmacy"])->paginate(20);
        return view("admin.orders", compact("orders"));
    }
}