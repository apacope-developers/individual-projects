<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckStockLevels;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check stock levels every hour
        $schedule->command('app:check-stock-levels')
            ->hourly()
            ->description('Check medicine stock levels and send notifications')
            ->withoutOverlapping()
            ->runInBackground()
            ->onSuccess(function () {
                \Log::info('Stock level check completed successfully');
            })
            ->onFailure(function () {
                \Log::error('Stock level check failed');
            });
        
        // Also run at 9 AM daily for comprehensive check
        $schedule->command('app:check-stock-levels')
            ->dailyAt('09:00')
            ->description('Daily comprehensive stock level check')
            ->withoutOverlapping()
            ->runInBackground();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
