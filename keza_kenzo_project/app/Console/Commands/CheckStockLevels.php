<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StockMonitoringService;
use Illuminate\Support\Facades\Log;

class CheckStockLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-stock-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check medicine stock levels and send low stock notifications';

    /**
     * Execute the console command.
     */
    public function handle(StockMonitoringService $stockService)
    {
        $this->info('Starting stock level monitoring...');
        
        try {
            $result = $stockService->checkLowStock();
            
            $this->info('Stock monitoring completed successfully!');
            $this->info('Low stock items found: ' . $result['low_stock_count']);
            $this->info('Notifications sent: ' . $result['notifications_sent']);
            
            if ($result['low_stock_count'] > 0) {
                $this->warn('⚠️  Some medicines are running low on stock!');
                foreach ($result['items'] as $item) {
                    $severity = $item['severity'] === 'critical' ? '🚨 CRITICAL' : '⚠️  WARNING';
                    $this->line(
                        $severity . ' - ' . 
                        $item['medicine']->name . ' at ' . 
                        $item['pharmacy']->name . 
                        ' (' . $item['current_stock'] . ' units remaining)'
                    );
                }
            } else {
                $this->info('✅ All medicine stocks are at healthy levels!');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Stock monitoring failed: ' . $e->getMessage());
            Log::error('Stock monitoring command failed: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
