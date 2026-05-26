<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📦 Order Confirmation - ' . $this->order->order_id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Thank you for your order! Here are your order details:')
            ->line('**Order ID:** ' . $this->order->order_id)
            ->line('**Medicine:** ' . $this->order->medicine_name)
            ->line('**Pharmacy:** ' . $this->order->pharmacy_name)
            ->line('**Quantity:** ' . $this->order->quantity)
            ->line('**Total Amount:** RWF ' . number_format($this->order->total_amount, 0))
            ->line('**Payment Method:** ' . ucfirst($this->order->payment_method))
            ->line('**Status:** ' . ucfirst($this->order->status))
            ->action('View Order Details', route('orders.show', $this->order->id))
            ->line('Please keep this email for your records.')
            ->salutation('Best regards, MediFind Team');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Order Confirmed',
            'message' => 'Your order ' . $this->order->order_id . ' has been confirmed',
            'order_id' => $this->order->id,
            'medicine_name' => $this->order->medicine_name,
            'pharmacy_name' => $this->order->pharmacy_name,
            'total_amount' => $this->order->total_amount,
            'type' => 'order'
        ];
    }
}
