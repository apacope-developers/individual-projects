<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Order;
use App\Models\User;
use App\Models\Availability;
use App\Models\UserActivity;

class MobileApiController extends Controller
{
    /**
     * API Authentication
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('mobile_app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Get medicines with search and filters
     */
    public function getMedicines(Request $request)
    {
        $query = Medicine::query();

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by province
        if ($request->has('province')) {
            $query->whereHas('pharmacies', function ($q) use ($request) {
                $q->where('province', $request->province);
            });
        }

        // Get medicines with availability
        $medicines = $query->with(['availabilities.pharmacy'])
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $medicines->items(),
            'pagination' => [
                'current_page' => $medicines->currentPage(),
                'last_page' => $medicines->lastPage(),
                'per_page' => $medicines->perPage(),
                'total' => $medicines->total()
            ]
        ]);
    }

    /**
     * Get pharmacies with search and filters
     */
    public function getPharmacies(Request $request)
    {
        $query = Pharmacy::query();

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by district
        if ($request->has('district')) {
            $query->where('district', $request->district);
        }

        $pharmacies = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $pharmacies->items(),
            'pagination' => [
                'current_page' => $pharmacies->currentPage(),
                'last_page' => $pharmacies->lastPage(),
                'per_page' => $pharmacies->perPage(),
                'total' => $pharmacies->total()
            ]
        ]);
    }

    /**
     * Get medicine availability at pharmacies
     */
    public function getMedicineAvailability($medicineId, Request $request)
    {
        $query = Availability::with(['pharmacy', 'medicine'])
            ->where('medicine_id', $medicineId);

        // Filter by province
        if ($request->has('province')) {
            $query->whereHas('pharmacy', function ($q) use ($request) {
                $q->where('province', $request->province);
            });
        }

        $availabilities = $query->get();

        return response()->json([
            'success' => true,
            'data' => $availabilities->map(function ($availability) {
                return [
                    'id' => $availability->id,
                    'pharmacy' => [
                        'id' => $availability->pharmacy->id,
                        'name' => $availability->pharmacy->name,
                        'location' => $availability->pharmacy->location,
                        'district' => $availability->pharmacy->district,
                        'phone' => $availability->pharmacy->phone,
                        'email' => $availability->pharmacy->email
                    ],
                    'medicine' => [
                        'id' => $availability->medicine->id,
                        'name' => $availability->medicine->name,
                        'category' => $availability->medicine->category,
                        'description' => $availability->medicine->description
                    ],
                    'stock_quantity' => $availability->stock_quantity,
                    'unit_price' => $availability->unit_price,
                    'is_available' => $availability->stock_quantity > 0
                ];
            })
        ]);
    }

    /**
     * Place order
     */
    public function placeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medicine_id' => 'required|exists:medicines,id',
            'pharmacy_id' => 'required|exists:pharmacies,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,mobile_money,insurance',
            'phone_number' => 'required|string',
            'delivery_address' => 'required|string',
            'user_insurance_id' => 'nullable|exists:user_insurances,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $medicine = Medicine::find($request->medicine_id);
        $pharmacy = Pharmacy::find($request->pharmacy_id);
        
        // Check availability
        $availability = Availability::where('medicine_id', $request->medicine_id)
            ->where('pharmacy_id', $request->pharmacy_id)
            ->first();

        if (!$availability || $availability->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Medicine not available in requested quantity'
            ], 400);
        }

        // Calculate total
        $totalAmount = $availability->unit_price * $request->quantity;

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'medicine_id' => $request->medicine_id,
            'pharmacy_id' => $request->pharmacy_id,
            'order_id' => 'ORD-' . time() . '-' . rand(1000, 9999),
            'medicine_name' => $medicine->name,
            'pharmacy_name' => $pharmacy->name,
            'pharmacy_location' => $pharmacy->location,
            'quantity' => $request->quantity,
            'unit_price' => $availability->unit_price,
            'total_amount' => $totalAmount,
            'payment_method' => $request->payment_method,
            'phone_number' => $request->phone_number,
            'delivery_address' => $request->delivery_address,
            'status' => 'pending',
            'payment_status' => 'pending',
            'user_insurance_id' => $request->user_insurance_id
        ]);

        // Log activity
        UserActivity::logMedicinePurchase($user->id, $medicine->name, $pharmacy->name, $totalAmount);

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'data' => [
                'order_id' => $order->order_id,
                'total_amount' => $totalAmount,
                'estimated_delivery' => now()->addDays(2)->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Get user orders
     */
    public function getUserOrders(Request $request)
    {
        $user = Auth::user();
        
        $orders = Order::where('user_id', $user->id)
            ->with(['medicine', 'pharmacy'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $orders->items()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'medicine' => [
                        'name' => $order->medicine_name,
                        'id' => $order->medicine_id
                    ],
                    'pharmacy' => [
                        'name' => $order->pharmacy_name,
                        'location' => $order->pharmacy_location,
                        'id' => $order->pharmacy_id
                    ],
                    'quantity' => $order->quantity,
                    'total_amount' => $order->total_amount,
                    'payment_method' => $order->payment_method,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'can_cancel' => $order->canBeCancelled()
                ];
            }),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total()
            ]
        ]);
    }

    /**
     * Get order details
     */
    public function getOrderDetails($orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('user_id', $user->id)
            ->where('id', $orderId)
            ->with(['medicine', 'pharmacy', 'userInsurance'])
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $order->id,
                'order_id' => $order->order_id,
                'medicine' => [
                    'name' => $order->medicine_name,
                    'id' => $order->medicine_id
                ],
                'pharmacy' => [
                    'name' => $order->pharmacy_name,
                    'location' => $order->pharmacy_location,
                    'phone' => $order->pharmacy ? $order->pharmacy->phone : null,
                    'id' => $order->pharmacy_id
                ],
                'quantity' => $order->quantity,
                'unit_price' => $order->unit_price,
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment_method,
                'phone_number' => $order->phone_number,
                'delivery_address' => $order->delivery_address,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $order->updated_at->format('Y-m-d H:i:s'),
                'can_cancel' => $order->canBeCancelled(),
                'insurance' => $order->userInsurance ? [
                    'provider' => $order->userInsurance->insurance->name,
                    'policy_number' => $order->userInsurance->policy_number
                ] : null
            ]
        ]);
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        $user = Auth::user();
        
        $order = Order::where('user_id', $user->id)
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled at this stage'
            ], 400);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully'
        ]);
    }

    /**
     * Get user profile
     */
    public function getProfile()
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'insurances' => $user->userInsurances->map(function ($insurance) {
                    return [
                        'id' => $insurance->id,
                        'provider' => $insurance->insurance->name,
                        'policy_number' => $insurance->policy_number,
                        'is_primary' => $insurance->is_primary
                    ];
                })
            ]
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $user->update($request->only(['name', 'phone', 'address']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address
            ]
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
