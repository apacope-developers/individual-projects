<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Fetch real data from database tables
        $doctors = \App\Models\Doctor::where('status', 'active')->get();
        $users = \App\Models\User::all();
        $emergencyContacts = \App\Models\EmergencyContact::all();
        $firstAidGuides = \App\Models\FirstAidGuide::orderBy('sort_order')->get();
        
        // Debug: Log the counts
        \Log::info('Dashboard data counts: doctors=' . $doctors->count() . ', users=' . $users->count() . ', contacts=' . $emergencyContacts->count() . ', guides=' . $firstAidGuides->count());
        
        return view('dashboard', compact('doctors', 'users', 'emergencyContacts', 'firstAidGuides'));
    }

    public function addContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|string|in:personal,medical,emergency',
            'icon' => 'sometimes|string'
        ]);
        
        $validated['is_default'] = false;
        
        \App\Models\EmergencyContact::create($validated);
        
        return response()->json(['success' => true, 'message' => 'Contact added successfully']);
    }

    public function getGuideDetails($id)
    {
        $guide = \App\Models\FirstAidGuide::find($id);
        
        if ($guide) {
            return response()->json(['success' => true, 'guide' => $guide]);
        }
        
        return response()->json(['success' => false, 'message' => 'Guide not found']);
    }

    public function deleteContact($id)
    {
        $contact = \App\Models\EmergencyContact::find($id);
        
        if ($contact) {
            $contact->delete();
            return response()->json(['success' => true, 'message' => 'Contact deleted successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Contact not found']);
    }
}