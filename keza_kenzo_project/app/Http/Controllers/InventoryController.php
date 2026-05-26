<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Availability;
use App\Models\Pharmacy;
use App\Services\BarcodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index()
    {
        $pharmacy = Auth::user()->pharmacy;
        $availabilities = Availability::with(['medicine'])
            ->where('pharmacy_id', $pharmacy->id)
            ->orderBy('medicine_name')
            ->paginate(20);

        return view('pharmacist.inventory.index', compact('availabilities'));
    }

    public function scanBarcode()
    {
        return view('pharmacist.inventory.scan');
    }

    public function processBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string'
        ]);

        $result = BarcodeService::scanBarcode($request->barcode);

        if ($result['success']) {
            $pharmacy = Auth::user()->pharmacy;
            $availability = Availability::where('medicine_id', $result['medicine']->id)
                ->where('pharmacy_id', $pharmacy->id)
                ->first();

            if ($availability) {
                return response()->json([
                    'success' => true,
                    'medicine' => $result['medicine'],
                    'availability' => $availability,
                    'message' => 'Medicine found in your inventory'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'medicine' => $result['medicine'],
                    'message' => 'Medicine not found in your inventory. Would you like to add it?'
                ]);
            }
        }

        return response()->json($result);
    }

    public function generateBarcode($medicineId)
    {
        $medicine = Medicine::findOrFail($medicineId);
        
        if (!$medicine->barcode) {
            $medicine->barcode = BarcodeService::generateBarcodeNumber();
            $medicine->qr_code = BarcodeService::generateQRCodeData($medicine);
            $medicine->save();
        }

        $barcodeData = BarcodeService::generateBarcodeImage($medicine->barcode);
        $qrData = BarcodeService::generateQRCodeHTML($medicine->qr_code);

        return view('admin.medicines.barcode', compact('medicine', 'barcodeData', 'qrData'));
    }

    public function bulkStockUpdate(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.availability_id' => 'required|exists:availabilities,id',
            'updates.*.stock' => 'required|integer|min:0',
            'updates.*.batch_number' => 'nullable|string',
            'updates.*.expiry_date' => 'nullable|date'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->updates as $update) {
                $availability = Availability::findOrFail($update['availability_id']);
                
                // Check if user has permission for this pharmacy
                if (Auth::user()->role === 'pharmacist') {
                    $pharmacy = Auth::user()->pharmacy;
                    if ($availability->pharmacy_id !== $pharmacy->id) {
                        throw new \Exception('Unauthorized access to pharmacy inventory');
                    }
                }

                $availability->stock = $update['stock'];
                
                if (isset($update['batch_number'])) {
                    $availability->batch_number = $update['batch_number'];
                }
                
                if (isset($update['expiry_date'])) {
                    $availability->expiry_date = $update['expiry_date'];
                }

                $availability->save();

                // Check for low stock alerts
                if ($availability->stock <= $availability->reorder_level) {
                    app('App\Http\Controllers\NotificationController')
                        ->sendLowStockAlert($availability);
                }
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating stock: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stockAdjustment($availabilityId)
    {
        $availability = Availability::with(['medicine', 'pharmacy'])->findOrFail($availabilityId);
        
        // Check permissions
        if (Auth::user()->role === 'pharmacist') {
            $pharmacy = Auth::user()->pharmacy;
            if ($availability->pharmacy_id !== $pharmacy->id) {
                abort(403, 'Unauthorized access');
            }
        }

        return view('pharmacist.inventory.adjust', compact('availability'));
    }

    public function processStockAdjustment(Request $request, $availabilityId)
    {
        $request->validate([
            'operation' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
            'batch_number' => 'nullable|string',
            'expiry_date' => 'nullable|date'
        ]);

        $availability = Availability::findOrFail($availabilityId);
        
        // Check permissions
        if (Auth::user()->role === 'pharmacist') {
            $pharmacy = Auth::user()->pharmacy;
            if ($availability->pharmacy_id !== $pharmacy->id) {
                abort(403, 'Unauthorized access');
            }
        }

        $oldStock = $availability->stock;
        $newStock = BarcodeService::updateStockLevels($availabilityId, $request->quantity, $request->operation);

        // Log the adjustment
        DB::table('stock_adjustments')->insert([
            'availability_id' => $availabilityId,
            'user_id' => Auth::id(),
            'operation' => $request->operation,
            'quantity' => $request->quantity,
            'old_stock' => $oldStock,
            'new_stock' => $newStock->stock,
            'reason' => $request->reason,
            'batch_number' => $request->batch_number,
            'expiry_date' => $request->expiry_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (isset($request->batch_number)) {
            $newStock->batch_number = $request->batch_number;
        }
        
        if (isset($request->expiry_date)) {
            $newStock->expiry_date = $request->expiry_date;
        }
        
        $newStock->save();

        return redirect()->route('pharmacist.inventory.index')
            ->with('success', 'Stock adjusted successfully');
    }

    public function expiryAlerts()
    {
        $pharmacy = Auth::user()->pharmacy;
        $expiryAlerts = BarcodeService::getExpiryAlerts($pharmacy->id);
        
        return view('pharmacist.inventory.expiry', compact('expiryAlerts'));
    }

    public function reorderSuggestions()
    {
        $pharmacy = Auth::user()->pharmacy;
        $reorderSuggestions = BarcodeService::getReorderSuggestions($pharmacy->id);
        
        return view('pharmacist.inventory.reorder', compact('reorderSuggestions'));
    }

    public function inventoryReport()
    {
        $pharmacy = Auth::user()->pharmacy;
        
        $data = [
            'total_items' => Availability::where('pharmacy_id', $pharmacy->id)->count(),
            'total_stock' => Availability::where('pharmacy_id', $pharmacy->id)->sum('stock'),
            'total_value' => Availability::where('pharmacy_id', $pharmacy->id)
                ->with('medicine')
                ->get()
                ->sum(function($availability) {
                    return $availability->stock * $availability->price;
                }),
            'low_stock_items' => Availability::where('pharmacy_id', $pharmacy->id)
                ->whereColumn('stock', '<=', 'reorder_level')
                ->count(),
            'out_of_stock_items' => Availability::where('pharmacy_id', $pharmacy->id)
                ->where('stock', 0)
                ->count(),
            'expiring_soon' => BarcodeService::getExpiryAlerts($pharmacy->id)->count(),
            'categories' => Availability::where('pharmacy_id', $pharmacy->id)
                ->with('medicine')
                ->get()
                ->groupBy('medicine.category')
                ->map(function($items) {
                    return [
                        'count' => $items->count(),
                        'total_stock' => $items->sum('stock'),
                        'total_value' => $items->sum(function($item) {
                            return $item->stock * $item->price;
                        })
                    ];
                })
        ];

        return view('pharmacist.inventory.report', compact('data'));
    }

    public function generateInventoryLabels()
    {
        $pharmacy = Auth::user()->pharmacy;
        $availabilities = Availability::with(['medicine'])
            ->where('pharmacy_id', $pharmacy->id)
            ->where('stock', '>', 0)
            ->get();

        return view('pharmacist.inventory.labels', compact('availabilities'));
    }
}
