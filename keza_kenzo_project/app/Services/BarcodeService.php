<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Availability;
use Illuminate\Support\Str;

class BarcodeService
{
    public static function generateBarcodeNumber()
    {
        // Generate a unique 13-digit barcode number (EAN-13 format)
        do {
            $barcode = '';
            for ($i = 0; $i < 12; $i++) {
                $barcode .= mt_rand(0, 9);
            }
            
            // Calculate checksum digit for EAN-13
            $checksum = self::calculateEAN13Checksum($barcode);
            $barcode .= $checksum;
            
        } while (Medicine::where('barcode', $barcode)->exists());
        
        return $barcode;
    }
    
    public static function generateQRCodeData($medicine)
    {
        $data = [
            'type' => 'medicine',
            'id' => $medicine->id,
            'name' => $medicine->name,
            'barcode' => $medicine->barcode,
            'manufacturer' => $medicine->manufacturer,
            'strength' => $medicine->strength,
            'dosage_form' => $medicine->dosage_form,
            'requires_prescription' => $medicine->requires_prescription,
            'generated_at' => now()->toISOString(),
            'system' => 'MediFind'
        ];
        
        return json_encode($data);
    }
    
    public static function generateBatchNumber()
    {
        // Generate unique batch number
        $prefix = 'BATCH';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));
        
        return $prefix . $date . $random;
    }
    
    public static function generateStorageLocation($pharmacyId)
    {
        // Generate systematic storage location
        $sections = ['A', 'B', 'C', 'D', 'E'];
        $shelves = range(1, 20);
        $positions = range(1, 5);
        
        $section = $sections[array_rand($sections)];
        $shelf = $shelves[array_rand($shelves)];
        $position = $positions[array_rand($positions)];
        
        return "PH{$pharmacyId}-{$section}{$shelf}-{$position}";
    }
    
    public static function validateBarcode($barcode)
    {
        // Validate EAN-13 barcode format
        if (!preg_match('/^\d{13}$/', $barcode)) {
            return false;
        }
        
        // Verify checksum
        $checksum = self::calculateEAN13Checksum(substr($barcode, 0, 12));
        
        return $checksum == substr($barcode, -1);
    }
    
    public static function calculateEAN13Checksum($barcode)
    {
        $sum = 0;
        $length = strlen($barcode);
        
        for ($i = 0; $i < $length; $i++) {
            $digit = (int) $barcode[$i];
            $multiplier = ($i % 2 === 0) ? 1 : 3;
            $sum += $digit * $multiplier;
        }
        
        $checksum = (10 - ($sum % 10)) % 10;
        
        return $checksum;
    }
    
    public static function generateBarcodeImage($barcode, $width = 200, $height = 80)
    {
        // Simple barcode image generation using HTML/CSS representation
        $binary = '';
        $patterns = [
            '0' => '0001101', '1' => '0011001', '2' => '0010011', '3' => '0111101',
            '4' => '0100011', '5' => '0110001', '6' => '0101111', '7' => '0111011',
            '8' => '0110111', '9' => '0001011'
        ];
        
        // Convert barcode to binary pattern
        for ($i = 0; $i < strlen($barcode); $i++) {
            $digit = $barcode[$i];
            $binary .= $patterns[$digit] ?? '';
        }
        
        // Add start and stop markers
        $binary = '101' . $binary . '101';
        
        return [
            'binary' => $binary,
            'width' => $width,
            'height' => $height,
            'barcode' => $barcode
        ];
    }
    
    public static function generateQRCodeHTML($data, $size = 200)
    {
        // Generate a simple QR code representation using CSS grid
        $hash = md5($data);
        $gridSize = 25;
        $grid = [];
        
        // Create pseudo-random pattern based on data hash
        for ($i = 0; $i < $gridSize; $i++) {
            for ($j = 0; $j < $gridSize; $j++) {
                $index = ($i * $gridSize + $j) % strlen($hash);
                $bit = hexdec($hash[$index]) % 2;
                $grid[$i][$j] = $bit;
            }
        }
        
        return [
            'grid' => $grid,
            'size' => $size,
            'data' => $data
        ];
    }
    
    public static function scanBarcode($barcode)
    {
        $medicine = Medicine::where('barcode', $barcode)->first();
        
        if (!$medicine) {
            return [
                'success' => false,
                'message' => 'Medicine not found'
            ];
        }
        
        return [
            'success' => true,
            'medicine' => $medicine,
            'message' => 'Medicine found successfully'
        ];
    }
    
    public static function getExpiryAlerts($pharmacyId = null)
    {
        $query = Availability::with(['medicine', 'pharmacy'])
            ->where('expiry_date', '<=', now()->addDays(90))
            ->where('expiry_date', '>', now());
            
        if ($pharmacyId) {
            $query->where('pharmacy_id', $pharmacyId);
        }
        
        return $query->orderBy('expiry_date')->get();
    }
    
    public static function getReorderSuggestions($pharmacyId = null)
    {
        $query = Availability::with(['medicine', 'pharmacy'])
            ->whereColumn('stock', '<=', 'reorder_level');
            
        if ($pharmacyId) {
            $query->where('pharmacy_id', $pharmacyId);
        }
        
        return $query->orderBy('stock')->get();
    }
    
    public static function updateStockLevels($availabilityId, $quantity, $operation = 'add')
    {
        $availability = Availability::findOrFail($availabilityId);
        
        switch ($operation) {
            case 'add':
                $availability->stock += $quantity;
                break;
            case 'subtract':
                $availability->stock = max(0, $availability->stock - $quantity);
                break;
            case 'set':
                $availability->stock = $quantity;
                break;
        }
        
        $availability->save();
        
        // Check if stock is below reorder level
        if ($availability->stock <= $availability->reorder_level) {
            // Trigger reorder notification
            app('App\Http\Controllers\NotificationController')
                ->sendLowStockAlert($availability);
        }
        
        return $availability;
    }
}
