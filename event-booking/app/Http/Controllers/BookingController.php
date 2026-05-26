<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Store a new booking
     */
    public function store(Request $request, $eventId)
    {
        // 🔐 MUST be logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        // ✅ Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'ticket_type' => 'required|in:vip,regular',
        ]);

        // ✅ FORCE correct user_id (important)
        $booking = Booking::create([
            'name' => $request->name,
            'email' => $request->email,
            'ticket_type' => $request->ticket_type,
            'event_id' => $eventId,
            'user_id' => Auth::id(), // ✅ THIS FIXES YOUR ISSUE
        ]);

        return redirect()->route('payments.show', $booking->id)
            ->with('success', 'Booking created successfully!');
    }

    /**
     * Show logged-in user's bookings
     */
    public function myBookings()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bookings = Booking::with('event')
            ->where('user_id', Auth::id()) // ✅ only YOUR bookings
            ->latest()
            ->get();

        return view('bookings.my', compact('bookings'));
    }
}