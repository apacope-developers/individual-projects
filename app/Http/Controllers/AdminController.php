<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user() || !$request->user()->is_admin) {
                abort(403, 'Access Denied');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $users = User::where('is_admin', false)->latest()->get();
        $adminCount = User::where('is_admin', true)->count();
        $userCount = User::where('is_admin', false)->count();
        return view('admin', compact('users', 'adminCount', 'userCount'));
    }

    public function deleteUser(User $user)
    {
        if ($user->is_admin) {
            abort(403, 'Cannot delete admin.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // Body Map CRUD
    public function bodyMapIndex()
    {
        $zones = [
            'head' => ['label' => 'Head', 'conditions' => []],
            'chest' => ['label' => 'Chest', 'conditions' => []],
            'abdomen' => ['label' => 'Abdomen', 'conditions' => []],
            'left-arm' => ['label' => 'Left Arm', 'conditions' => []],
            'right-arm' => ['label' => 'Right Arm', 'conditions' => []],
            'left-leg' => ['label' => 'Left Leg', 'conditions' => []],
            'right-leg' => ['label' => 'Right Leg', 'conditions' => []]
        ];
        return view('admin.body-map', compact('zones'));
    }

    public function storeBodyMapCondition(Request $request)
    {
        $validated = $request->validate([
            'zone' => 'required|in:head,chest,abdomen,left-arm,right-arm,left-leg,right-leg',
            'name' => 'required|string|max:255',
            'severity' => 'required|in:critical,urgent,moderate,minor',
            'description' => 'required|string',
            'action' => 'required|string'
        ]);

        // Store in session or database (simplified for demo)
        session()->push('body_map_conditions.' . $validated['zone'], $validated);
        
        return back()->with('success', 'Condition added successfully.');
    }

    // Symptom Checker CRUD
    public function symptomCheckerIndex()
    {
        $questions = [
            'chest' => ['question' => 'Sudden or gradual?', 'options' => []],
            'breathing' => ['question' => 'Can they speak or clutching throat?', 'options' => []],
            'bleeding' => ['question' => 'How would you describe it?', 'options' => []],
            'unconscious' => ['question' => 'Is the person breathing?', 'options' => []],
            'burn' => ['question' => 'How large?', 'options' => []],
            'seizure' => ['question' => 'Currently seizing?', 'options' => []],
            'fracture' => ['question' => 'Bone visible or deformed?', 'options' => []],
            'allergic' => ['question' => 'Severe reaction?', 'options' => []]
        ];
        return view('admin.symptom-checker', compact('questions'));
    }

    public function storeSymptomCheckerOption(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:chest,breeding,bleeding,unconscious,burn,seizure,fracture,allergic',
            'option_text' => 'required|string|max:255',
            'result_severity' => 'required|in:critical,urgent,moderate,minor',
            'result_condition' => 'required|string|max:255',
            'result_action' => 'required|string'
        ]);

        session()->push('symptom_checker_options.' . $validated['category'], $validated);
        
        return back()->with('success', 'Option added successfully.');
    }

    // First Aid Guide CRUD
    public function firstAidGuideIndex()
    {
        $conditions = session('first_aid_conditions', []);
        return view('admin.first-aid-guide', compact('conditions'));
    }

    public function storeFirstAidCondition(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:50|unique:first_aid_conditions',
            'name' => 'required|string|max:255',
            'icon' => 'required|string|max:50',
            'severity' => 'required|in:critical,urgent,moderate,minor',
            'category' => 'required|in:cardiac,breathing,wounds,neurological,musculoskeletal,allergic,environmental',
            'summary' => 'required|string',
            'steps' => 'required|array|min:1',
            'steps.*' => 'required|string',
            'dos' => 'required|array|min:1',
            'dos.*' => 'required|string',
            'donts' => 'required|array|min:1',
            'donts.*' => 'required|string',
            'call_912' => 'required|boolean'
        ]);

        session()->push('first_aid_conditions', $validated);
        
        return back()->with('success', 'Condition added successfully.');
    }

    // Kit Inventory CRUD
    public function kitInventoryIndex()
    {
        $categories = session('kit_categories', [
            'bandages' => ['label' => 'Bandages & Dressings', 'icon' => 'fa-bandage', 'items' => []],
            'medications' => ['label' => 'Medications', 'icon' => 'fa-pills', 'items' => []],
            'tools' => ['label' => 'Tools & Equipment', 'icon' => 'fa-screwdriver-wrench', 'items' => []],
            'other' => ['label' => 'Other Essentials', 'icon' => 'fa-box', 'items' => []]
        ]);
        return view('admin.kit-inventory', compact('categories'));
    }

    public function storeKitItem(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:bandages,medications,tools,other',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0'
        ]);

        session()->push('kit_items.' . $validated['category'], $validated);
        
        return back()->with('success', 'Item added successfully.');
    }

    // Emergency Contacts CRUD
    public function contactsIndex()
    {
        // Initialize session data if not exists
        if (!session()->has('emergency_contacts')) {
            session(['emergency_contacts' => [
                ['id' => 'default_1', 'name' => 'Emergency Services', 'phone' => '912', 'type' => 'emergency', 'icon' => 'fa-phone-volume', 'isDefault' => true],
                ['id' => 'default_2', 'name' => 'Poison Control', 'phone' => '1-800-222-1222', 'type' => 'emergency', 'icon' => 'fa-skull-crossbones', 'isDefault' => true],
                ['id' => 'custom_1', 'name' => 'Dr. Sarah Mitchell', 'phone' => '555-0142', 'type' => 'medical', 'icon' => 'fa-user-doctor', 'isDefault' => false],
                ['id' => 'custom_2', 'name' => 'Mom', 'phone' => '555-0198', 'type' => 'personal', 'icon' => 'fa-user', 'isDefault' => false]
            ]]);
        }
        
        $contacts = session('emergency_contacts');
        
        // Debug: Log current session state
        file_put_contents(storage_path('session_debug.log'), "PAGE LOAD - Current contacts: " . json_encode($contacts) . "\n");
        
        return view('admin.contacts', compact('contacts'));
    }

    public function storeContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|in:emergency,medical,personal',
            'icon' => 'required|string|max:50',
            'isDefault' => 'required'
        ]);

        // Convert string to boolean
        $validated['isDefault'] = $validated['isDefault'] === '1' || $validated['isDefault'] === true;
        $validated['id'] = 'custom_' . uniqid();
        $contacts = session('emergency_contacts', []);
        $contacts[] = $validated;
        session(['emergency_contacts' => $contacts]);
        
        return back()->with('success', 'Contact added successfully.');
    }

    public function updateContact(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|in:emergency,medical,personal',
            'icon' => 'required|string|max:50',
            'isDefault' => 'required'
        ]);

        // Convert string to boolean
        $validated['isDefault'] = $validated['isDefault'] === '1' || $validated['isDefault'] === true;

        $contacts = session('emergency_contacts', []);
        
        // Find contact by ID
        $contactIndex = null;
        foreach ($contacts as $index => $contact) {
            if ($contact['id'] === $id) {
                $contactIndex = $index;
                break;
            }
        }
        
        if ($contactIndex !== null) {
            $contacts[$contactIndex] = array_merge($contacts[$contactIndex], $validated);
            session(['emergency_contacts' => $contacts]);
            return back()->with('success', 'Contact updated successfully.');
        }
        
        return back()->with('error', 'Contact not found.');
    }

    public function deleteContact($id)
    {
        $contacts = session('emergency_contacts', []);
        
        // Debug: Write to file to see what's happening
        $debug = "DELETE REQUEST - ID: $id\n";
        $debug .= "Contacts in session: " . json_encode($contacts) . "\n";
        
        // Find contact by ID
        $contactIndex = null;
        foreach ($contacts as $index => $contact) {
            $debug .= "Checking index $index: " . json_encode($contact) . "\n";
            if ($contact['id'] === $id) {
                $debug .= "FOUND MATCH at index $index\n";
                $contactIndex = $index;
                break;
            }
        }
        
        $debug .= "Final contactIndex: $contactIndex\n";
        
        // Write debug info to storage
        file_put_contents(storage_path('delete_debug.log'), $debug);
        
        if ($contactIndex !== null) {
            $contact = $contacts[$contactIndex];
            file_put_contents(storage_path('delete_debug.log'), "CONTACT FOUND: " . json_encode($contact) . "\n", FILE_APPEND);
            file_put_contents(storage_path('delete_debug.log'), "IS DEFAULT CHECK: " . ($contact['isDefault'] ? 'TRUE' : 'FALSE') . "\n", FILE_APPEND);
            
            if ($contact['isDefault'] === true || $contact['isDefault'] === 1) {
                file_put_contents(storage_path('delete_debug.log'), "CONTACT IS DEFAULT - ABORTING DELETE\n", FILE_APPEND);
                return back()->with('error', 'Cannot delete default emergency contacts.');
            }
            
            // Remove the contact
            unset($contacts[$contactIndex]);
            $contacts = array_values($contacts);
            
            // Force session update and redirect
            session()->forget('emergency_contacts');
            session()->put('emergency_contacts', $contacts);
            
            file_put_contents(storage_path('delete_debug.log'), "CONTACT DELETED - New session: " . json_encode($contacts) . "\n", FILE_APPEND);
            file_put_contents(storage_path('delete_debug.log'), "REDIRECTING TO admin.contacts\n", FILE_APPEND);
            
            return redirect()->route('admin.contacts')->with('success', 'Contact deleted successfully.');
        } else {
            file_put_contents(storage_path('delete_debug.log'), "CONTACT NOT FOUND - ABORTING DELETE\n", FILE_APPEND);
        }
        
        return back()->with('error', 'Contact not found.');
    }
    
    // Manual test route - force delete specific contact
    public function forceDelete($id)
    {
        $contacts = session('emergency_contacts', []);
        
        // Find and remove contact
        $contacts = array_filter($contacts, function($contact) use ($id) {
            return $contact['id'] !== $id;
        });
        
        // Re-index array
        $contacts = array_values($contacts);
        
        // Update session
        session(['emergency_contacts' => $contacts]);
        
        return redirect()->route('admin.contacts')->with('success', "Force deleted contact $id. Remaining: " . count($contacts));
    }
}