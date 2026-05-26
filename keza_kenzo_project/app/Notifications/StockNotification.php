<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Medicine;
use App\Models\Pharmacy;

class StockNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $medicine;
    public $pharmacy;
    public $currentStock;
    public $threshold;

    /**
     * Create a new notification instance.
     */
    public function __construct(Medicine $medicine, Pharmacy $pharmacy, int $currentStock, int $threshold)
    {
        $this->medicine = $medicine;
        $this->pharmacy = $pharmacy;
        $this->currentStock = $currentStock;
        $this->threshold = $threshold;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🚨 Low Stock Alert: ' . $this->medicine->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is an automated notification about low stock levels.')
            ->line('**Medicine:** ' . $this->medicine->name)
            ->line('**Pharmacy:** ' . $this->pharmacy->name)
            ->line('**Current Stock:** ' . $this->currentStock . ' units')
            ->line('**Threshold:** ' . $this->threshold . ' units')
            ->line('**Location:** ' . $this->pharmacy->location)
            ->line('**Phone:** ' . $this->pharmacy->phone)
            ->action('View Stock Details', route('admin.availabilities'))
            ->line('Please restock this medicine as soon as possible to avoid stockouts.')
            ->line('Thank you for using MediFind Medical Center!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Low Stock Alert',
            'message' => $this->medicine->name . ' is running low at ' . $this->pharmacy->name,
            'medicine_id' => $this->medicine->id,
            'pharmacy_id' => $this->pharmacy->id,
            'current_stock' => $this->currentStock,
            'threshold' => $this->threshold,
            'medicine_name' => $this->medicine->name,
            'pharmacy_name' => $this->pharmacy->name,
            'pharmacy_location' => $this->pharmacy->location,
            'severity' => $this->currentStock <= 5 ? 'critical' : 'warning',
            'action_url' => route('admin.availabilities'),
        ];
    }
}
