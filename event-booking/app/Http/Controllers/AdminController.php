<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class AdminController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $bookings = Booking::with('event')->latest()->get();

        return view('admin.bookings', compact('bookings'));
    }
}