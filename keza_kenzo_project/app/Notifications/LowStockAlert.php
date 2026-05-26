<?php

namespace App\Notifications;

use App\Models\Availability;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class LowStockAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $availability;

    public function __construct(Availability $availability)
    {
        $this->availability = $availability;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⚠️ Low Stock Alert - ' . $this->availability->medicine->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is an automated alert to inform you that a medicine is running low on stock.')
            ->line('**Medicine:** ' . $this->availability->medicine->name)
            ->line('**Pharmacy:** ' . $this->availability->pharmacy->name)
            ->line('**Current Stock:** ' . $this->availability->stock . ' units')
            ->line('**Price:** RWF ' . number_format($this->availability->price, 0))
            ->action('Manage Stock', route('admin.availabilities'))
            ->line('Please restock this item soon to avoid stockouts.')
            ->salutation('Best regards, MediFind System');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Low Stock Alert',
            'message' => $this->availability->medicine->name . ' is running low on stock at ' . $this->availability->pharmacy->name,
            'availability_id' => $this->availability->id,
            'medicine_id' => $this->availability->medicine_id,
            'pharmacy_id' => $this->availability->pharmacy_id,
            'stock_level' => $this->availability->stock,
            'type' => 'low_stock'
        ];
    }
}
