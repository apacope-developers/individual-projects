<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Availability;
use App\Models\UserInsurance;
use App\Models\Insurance;
use App\Models\Order;
use App\Models\UserActivity;
use App\Models\RecentOrder;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        // For demo purposes, allow payments without authentication
        // In production, you would uncomment the authentication check
        /*
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated. Please login to continue.'
            ], 401);
        }
        */

        $validated = $request->validate([
            'medicine_id' => 'required|integer',
            'pharmacy_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:mobile,card,cash,insurance',
            'phone_number' => 'required_if:payment_method,mobile',
            'delivery_address' => 'required|string|min:5',
            'card_number' => 'required_if:payment_method,card',
            'expiry_date' => 'required_if:payment_method,card',
            'cvv' => 'required_if:payment_method,card',
            'cardholder_name' => 'required_if:payment_method,card',
            'user_insurance_id' => 'required_if:payment_method,insurance|exists:user_insurances,id'
        ]);

        try {
            // Get medicine and pharmacy details
            $medicine = Medicine::find($validated['medicine_id']);
            $pharmacy = Pharmacy::find($validated['pharmacy_id']);
            
            // If medicine not found (mock medicine), create temporary data
            if (!$medicine) {
                $medicineName = 'Unknown Medicine';
                $unitPrice = $request->input('unit_price', 1000); // Default price for mock medicines
            } else {
                $medicineName = $medicine->name;
                $unitPrice = $medicine->price;
            }
            
            // If pharmacy not found, use default
            if (!$pharmacy) {
                $pharmacyName = 'Unknown Pharmacy';
                $pharmacyLocation = 'Unknown Location';
            } else {
                $pharmacyName = $pharmacy->name;
                $pharmacyLocation = $pharmacy->location;
            }
            
            // Calculate total
            $totalAmount = $unitPrice * $validated['quantity'];
            
            // Generate order ID
            $orderId = 'ORD-' . strtoupper(uniqid());
            
            // Process payment based on method
            $paymentResult = $this->processPaymentMethod($validated, $totalAmount, $orderId);
            
            if ($paymentResult['success']) {
                // Save order to database
                $order = Order::create([
                    'user_id' => Auth::check() ? Auth::id() : 1,
                    'medicine_id' => $validated['medicine_id'],
                    'pharmacy_id' => $validated['pharmacy_id'],
                    'order_id' => $orderId,
                    'medicine_name' => $medicineName,
                    'pharmacy_name' => $pharmacyName,
                    'pharmacy_location' => $pharmacyLocation,
                    'quantity' => $validated['quantity'],
                    'unit_price' => $unitPrice,
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'phone_number' => $validated['phone_number'] ?? null,
                    'delivery_address' => $validated['delivery_address'],
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'transaction_id' => $paymentResult['transaction_id'],
                ]);
                
                // Add insurance information if applicable
                if ($validated['payment_method'] === 'insurance') {
                    $order->update([
                        'user_insurance_id' => $validated['user_insurance_id'],
                        'insurance_provider' => $paymentResult['insurance_provider'] ?? null,
                        'insurance_code' => $paymentResult['insurance_code'] ?? null,
                        'policy_number' => $paymentResult['policy_number'] ?? null,
                        'coverage_percentage' => $paymentResult['coverage_percentage'] ?? null,
                        'insurance_coverage' => $paymentResult['insurance_coverage'] ?? 0,
                        'patient_portion' => $paymentResult['patient_portion'] ?? $totalAmount,
                        'claim_id' => $paymentResult['claim_id'] ?? null,
                        'claim_status' => $paymentResult['claim_status'] ?? null,
                    ]);
                }
                
                // Log user activity
                if (Auth::check()) {
                    UserActivity::logMedicinePurchase(
                        Auth::id(),
                        $order->id,
                        $medicineName,
                        $request->ip(),
                        $request->userAgent()
                    );
                    
                    // Save to recent orders automatically
                    $this->saveRecentOrder($order);
                }
                
                // Send confirmation email
                $this->sendConfirmationEmail($order->toArray());
                
                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully!',
                    'order_id' => $orderId,
                    'order_data' => $order->toArray()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed: ' . $paymentResult['error']
                ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function processPaymentMethod($data, $amount, $orderId)
    {
        $paymentMethod = $data['payment_method'];
        
        switch ($paymentMethod) {
            case 'mobile':
                return $this->processMobileMoney($data, $amount, $orderId);
            case 'card':
                return $this->processCardPayment($data, $amount, $orderId);
            case 'cash':
                return $this->processCashOnDelivery($data, $amount, $orderId);
            case 'insurance':
                return $this->processInsurancePayment($data, $amount, $orderId);
            default:
                return ['success' => false, 'error' => 'Invalid payment method'];
        }
    }
    
    private function processMobileMoney($data, $amount, $orderId)
    {
        try {
            // Simulate Mobile Money payment (MTN/Airtel)
            $phoneNumber = $data['phone_number'];
            
            \Log::info('Processing mobile money payment', [
                'phone' => $phoneNumber,
                'amount' => $amount,
                'order_id' => $orderId
            ]);
            
            // Validate phone number (more flexible)
            // Remove spaces, dashes, and parentheses
            $cleanPhone = preg_replace('/[\s\-\(\)]/', '', $phoneNumber);
            
            // Accept various formats: +250, 07, or just numbers
            if (!preg_match('/^(\+250|0)?7[238]\d{7}$/', $cleanPhone) && strlen($cleanPhone) >= 9) {
                // If not a valid Rwandan number, still allow for demo purposes
                // In production, you might want stricter validation
                \Log::warning('Using non-Rwandese phone number for demo', ['phone' => $phoneNumber]);
            }
            
            // Simulate payment processing (reduced sleep time for better UX)
            usleep(1000000); // 1 second instead of 2
            
            // Generate transaction ID
            $transactionId = 'MM-' . strtoupper(uniqid());
            
            \Log::info('Mobile money payment successful', [
                'transaction_id' => $transactionId,
                'order_id' => $orderId
            ]);
            
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'payment_method' => 'Mobile Money',
                'phone_number' => $phoneNumber
            ];
            
        } catch (\Exception $e) {
            \Log::error('Mobile money payment failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            
            return [
                'success' => false,
                'error' => 'Payment processing failed: ' . $e->getMessage()
            ];
        }
    }
    
    private function processCardPayment($data, $amount, $orderId)
    {
        // Simulate card payment processing
        $cardNumber = $data['card_number'];
        $expiryDate = $data['expiry_date'];
        $cvv = $data['cvv'];
        
        // Basic card validation
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return ['success' => false, 'error' => 'Invalid card number'];
        }
        
        // Simulate payment processing
        sleep(2);
        
        // Generate transaction ID
        $transactionId = 'CARD-' . strtoupper(uniqid());
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'payment_method' => 'Credit/Debit Card',
            'card_ending' => '****' . substr($cardNumber, -4)
        ];
    }
    
    private function processCashOnDelivery($data, $amount, $orderId)
    {
        // Cash on delivery - always successful
        $transactionId = 'COD-' . strtoupper(uniqid());
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'payment_method' => 'Cash on Delivery',
            'note' => 'Payment to be made upon delivery'
        ];
    }
    
    private function processInsurancePayment($data, $amount, $orderId)
    {
        try {
            $userInsuranceId = $data['user_insurance_id'];
            
            // Get user insurance details
            $userInsurance = UserInsurance::with('insurance')
                ->where('id', $userInsuranceId)
                ->where('user_id', Auth::id())
                ->where('is_active', true)
                ->first();
            
            if (!$userInsurance) {
                return ['success' => false, 'error' => 'Invalid or inactive insurance plan'];
            }
            
            // Check if insurance is expired
            if ($userInsurance->expiry_date < now()) {
                return ['success' => false, 'error' => 'Insurance plan has expired'];
            }
            
            $insurance = $userInsurance->insurance;
            $coveragePercentage = $insurance->coverage_percentage;
            
            // Calculate insurance coverage and patient portion
            $insuranceCoverage = $amount * ($coveragePercentage / 100);
            $patientPortion = $amount - $insuranceCoverage;
            
            // Simulate insurance verification and claim processing
            \Log::info('Processing insurance payment', [
                'user_insurance_id' => $userInsuranceId,
                'insurance' => $insurance->name,
                'coverage_percentage' => $coveragePercentage,
                'total_amount' => $amount,
                'insurance_coverage' => $insuranceCoverage,
                'patient_portion' => $patientPortion,
                'order_id' => $orderId
            ]);
            
            // Simulate insurance processing time
            sleep(1);
            
            // Generate transaction IDs
            $transactionId = 'INS-' . strtoupper(uniqid());
            $claimId = 'CLAIM-' . strtoupper(uniqid());
            
            \Log::info('Insurance payment successful', [
                'transaction_id' => $transactionId,
                'claim_id' => $claimId,
                'order_id' => $orderId,
                'insurance_coverage' => $insuranceCoverage,
                'patient_portion' => $patientPortion
            ]);
            
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'claim_id' => $claimId,
                'payment_method' => 'Medical Insurance',
                'insurance_provider' => $insurance->name,
                'insurance_code' => $insurance->code,
                'policy_number' => $userInsurance->policy_number,
                'coverage_percentage' => $coveragePercentage,
                'total_amount' => $amount,
                'insurance_coverage' => $insuranceCoverage,
                'patient_portion' => $patientPortion,
                'claim_status' => 'approved'
            ];
            
        } catch (\Exception $e) {
            \Log::error('Insurance payment failed', [
                'error' => $e->getMessage(),
                'order_id' => $orderId
            ]);
            
            return [
                'success' => false,
                'error' => 'Insurance payment processing failed: ' . $e->getMessage()
            ];
        }
    }
    
    private function sendConfirmationEmail($orderData)
    {
        try {
            // In a real application, you would send an actual email
            // For now, we'll just log it
            $userEmail = Auth::check() ? Auth::user()->email : 'demo@medifind.com';
            
            \Log::info('Order confirmation email sent', [
                'order_id' => $orderData['order_id'],
                'user_id' => $orderData['user_id'],
                'email' => $userEmail
            ]);
            
            // You could use Laravel's Mail facade here:
            // Mail::to($userEmail)->send(new OrderConfirmation($orderData));
            
        } catch (\Exception $e) {
            \Log::error('Failed to send confirmation email: ' . $e->getMessage());
        }
    }
    
    public function getOrderStatus($orderId)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        // Get the order data
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Generate tracking information
        $trackingSteps = $this->getTrackingSteps($order);
        
        return response()->json([
            'success' => true,
            'order' => [
                'order_id' => $order->order_id,
                'status' => $order->status,
                'payment_status' => $order->payment_status,
                'medicine_name' => $order->medicine_name,
                'pharmacy_name' => $order->pharmacy_name,
                'pharmacy_location' => $order->pharmacy_location,
                'quantity' => $order->quantity,
                'total_amount' => 'RWF ' . number_format($order->total_amount, 0),
                'created_at' => $order->created_at->format('M j, Y H:i'),
                'estimated_delivery' => $order->created_at->addDays(2)->format('M j, Y H:i'),
                'tracking_number' => 'TRK-' . strtoupper($order->order_id),
                'tracking_steps' => $trackingSteps
            ]
        ]);
    }

    private function getTrackingSteps($order)
    {
        $steps = [
            [
                'status' => 'confirmed',
                'title' => 'Order Confirmed',
                'description' => 'Your order has been confirmed and payment received',
                'completed' => true,
                'time' => $order->created_at->format('M j, Y H:i')
            ]
        ];

        // Add processing step if order is past initial stage
        if ($order->created_at->diffInHours(now()) > 1) {
            $steps[] = [
                'status' => 'processing',
                'title' => 'Processing',
                'description' => 'Your order is being processed by the pharmacy',
                'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                'time' => $order->created_at->addHours(2)->format('M j, Y H:i')
            ];
        }

        // Add shipped step if applicable
        if ($order->created_at->diffInHours(now()) > 6) {
            $steps[] = [
                'status' => 'shipped',
                'title' => 'Shipped',
                'description' => 'Your order has been shipped and is on the way',
                'completed' => $order->status === 'delivered',
                'time' => $order->created_at->addHours(6)->format('M j, Y H:i')
            ];
        }

        // Add delivered step if order is delivered
        if ($order->status === 'delivered') {
            $steps[] = [
                'status' => 'delivered',
                'title' => 'Delivered',
                'description' => 'Your order has been delivered successfully',
                'completed' => true,
                'time' => $order->updated_at->format('M j, Y H:i')
            ];
        }

        return $steps;
    }
    
    public function getPaymentReceipt($orderId)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        // Get the order data
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // Generate receipt data
        $receiptData = [
            'order_id' => $order->order_id,
            'receipt_number' => 'RCP-' . strtoupper(uniqid()),
            'date' => $order->created_at->format('Y-m-d H:i:s'),
            'customer_name' => Auth::user()->name,
            'customer_email' => Auth::user()->email,
            'customer_phone' => Auth::user()->phone,
            'customer_address' => Auth::user()->address,
            'items' => [
                [
                    'name' => $order->medicine_name,
                    'quantity' => $order->quantity,
                    'unit_price' => $order->unit_price,
                    'total' => $order->total_amount
                ]
            ],
            'pharmacy_name' => $order->pharmacy_name,
            'pharmacy_location' => $order->pharmacy_location,
            'subtotal' => $order->total_amount,
            'delivery_fee' => 0, // You can add delivery fee logic here
            'total' => $order->total_amount,
            'payment_method' => $order->payment_method,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'status' => $order->payment_status,
            'order_status' => $order->status
        ];
        
        return response()->json([
            'success' => true,
            'receipt' => $receiptData
        ]);
    }
    
    /**
     * Save order to recent orders automatically
     */
    private function saveRecentOrder(Order $order): void
    {
        try {
            // Delete old orders (keep only last 20)
            RecentOrder::forUser($order->user_id)
                ->orderBy('created_at', 'desc')
                ->offset(20)
                ->delete();

            // Create new recent order
            RecentOrder::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'medicine_name' => $order->medicine_name,
                'pharmacy_name' => $order->pharmacy_name,
                'pharmacy_location' => $order->pharmacy_location,
                'quantity' => $order->quantity,
                'total_amount' => $order->total_amount,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the payment process
            \Log::error('Failed to save recent order: ' . $e->getMessage());
        }
    }
}
