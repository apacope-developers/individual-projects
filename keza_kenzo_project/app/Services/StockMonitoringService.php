<?php

namespace App\Services;

use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\Availability;
use App\Models\User;
use App\Notifications\StockNotification;
use Illuminate\Support\Facades\Log;

class StockMonitoringService
{
    /**
     * Check for low stock items and send notifications
     */
    public function checkLowStock()
    {
        $lowStockItems = $this->getLowStockItems();
        $notificationsSent = 0;

        foreach ($lowStockItems as $item) {
            $adminUsers = $this->getAdminUsers();
            
            foreach ($adminUsers as $admin) {
                try {
                    $admin->notify(new StockNotification(
                        $item['medicine'],
                        $item['pharmacy'],
                        $item['current_stock'],
                        $item['threshold']
                    ));
                    $notificationsSent++;
                } catch (\Exception $e) {
                    Log::error('Failed to send stock notification to admin ' . $admin->id . ': ' . $e->getMessage());
                }
            }
        }

        Log::info('Stock monitoring completed. Sent ' . $notificationsSent . ' notifications for ' . count($lowStockItems) . ' low stock items.');
        
        return [
            'low_stock_count' => count($lowStockItems),
            'notifications_sent' => $notificationsSent,
            'items' => $lowStockItems
        ];
    }

    /**
     * Get all items with low stock
     */
    private function getLowStockItems()
    {
        $lowStockItems = [];
        
        $availabilities = Availability::with(['medicine', 'pharmacy'])
            ->where('stock_quantity', '<=', 10) // Low stock threshold
            ->get();

        foreach ($availabilities as $availability) {
            $threshold = $this->calculateThreshold($availability->medicine);
            
            if ($availability->stock_quantity <= $threshold) {
                $lowStockItems[] = [
                    'medicine' => $availability->medicine,
                    'pharmacy' => $availability->pharmacy,
                    'current_stock' => $availability->stock_quantity,
                    'threshold' => $threshold,
                    'severity' => $availability->stock_quantity <= 5 ? 'critical' : 'warning'
                ];
            }
        }

        return $lowStockItems;
    }

    /**
     * Calculate threshold for a medicine based on its usage
     */
    private function calculateThreshold($medicine)
    {
        // Base threshold is 10 units
        $baseThreshold = 10;
        
        // Adjust based on medicine type or usage patterns
        // For example, essential medicines might have higher thresholds
        if (in_array($medicine->category, ['antibiotics', 'painkillers', 'chronic_medications'])) {
            return $baseThreshold * 2; // 20 units for essential medicines
        }
        
        return $baseThreshold;
    }

    /**
     * Get admin users who should receive notifications
     */
    private function getAdminUsers()
    {
        return User::where('is_admin', true)
            ->orWhere('email', 'like', '%admin%')
            ->orWhere('email', 'like', '%manager%')
            ->get();
    }

    /**
     * Get stock status for a specific medicine
     */
    public function getMedicineStockStatus($medicineId)
    {
        $availabilities = Availability::with(['pharmacy'])
            ->where('medicine_id', $medicineId)
            ->get();

        $stockStatus = [];
        
        foreach ($availabilities as $availability) {
            $threshold = $this->calculateThreshold($availability->medicine);
            $status = 'normal';
            
            if ($availability->stock_quantity <= 5) {
                $status = 'critical';
            } elseif ($availability->stock_quantity <= $threshold) {
                $status = 'low';
            }
            
            $stockStatus[] = [
                'pharmacy' => $availability->pharmacy,
                'stock_quantity' => $availability->stock_quantity,
                'threshold' => $threshold,
                'status' => $status,
                'percentage' => $this->calculateStockPercentage($availability->stock_quantity, $threshold)
            ];
        }

        return $stockStatus;
    }

    /**
     * Calculate stock percentage relative to threshold
     */
    private function calculateStockPercentage($currentStock, $threshold)
    {
        return $threshold > 0 ? round(($currentStock / $threshold) * 100, 2) : 0;
    }

    /**
     * Get all medicines with stock alerts
     */
    public function getStockAlerts()
    {
        $lowStockItems = $this->getLowStockItems();
        
        return [
            'critical' => array_filter($lowStockItems, function($item) {
                return $item['severity'] === 'critical';
            }),
            'warning' => array_filter($lowStockItems, function($item) {
                return $item['severity'] === 'warning';
            }),
            'total_count' => count($lowStockItems),
            'critical_count' => count(array_filter($lowStockItems, function($item) {
                return $item['severity'] === 'critical';
            })),
            'warning_count' => count(array_filter($lowStockItems, function($item) {
                return $item['severity'] === 'warning';
            }))
        ];
    }
}
