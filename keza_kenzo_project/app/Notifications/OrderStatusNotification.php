<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;
    public $previousStatus;
    public $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $previousStatus = null)
    {
        $this->order = $order;
        $this->previousStatus = $previousStatus;
        $this->newStatus = $order->status;
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
        $statusMessages = [
            'pending' => 'Your order has been received and is being processed.',
            'confirmed' => 'Your order has been confirmed and is being prepared.',
            'preparing' => 'Your order is being prepared at the pharmacy.',
            'ready' => 'Your order is ready for pickup!',
            'out_for_delivery' => 'Your order is out for delivery.',
            'delivered' => 'Your order has been delivered successfully!',
            'cancelled' => 'Your order has been cancelled.',
            'refunded' => 'Your order has been refunded.'
        ];

        $statusIcons = [
            'pending' => '⏳',
            'confirmed' => '✅',
            'preparing' => '🔄',
            'ready' => '📦',
            'out_for_delivery' => '🚚',
            'delivered' => '✅',
            'cancelled' => '❌',
            'refunded' => '💰'
        ];

        $icon = $statusIcons[$this->newStatus] ?? '📋';
        $message = $statusMessages[$this->newStatus] ?? 'Your order status has been updated.';

        $mail = (new MailMessage)
            ->subject($icon . ' Order Status Update: ' . $this->order->order_id)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($message);

        // Add order details
        $mail->line('**Order Details:**')
            ->line('• Order ID: ' . $this->order->order_id)
            ->line('• Medicine: ' . $this->order->medicine_name)
            ->line('• Pharmacy: ' . $this->order->pharmacy_name)
            ->line('• Quantity: ' . $this->order->quantity)
            ->line('• Total: RWF ' . number_format($this->order->total_amount, 0))
            ->line('• Payment Method: ' . ucfirst($this->order->payment_method))
            ->line('• Status: ' . ucfirst(str_replace('_', ' ', $this->newStatus)));

        // Add previous status if available
        if ($this->previousStatus) {
            $mail->line('• Previous Status: ' . ucfirst(str_replace('_', ' ', $this->previousStatus)));
        }

        // Add delivery information if applicable
        if ($this->order->delivery_address) {
            $mail->line('• Delivery Address: ' . $this->order->delivery_address);
        }

        // Add action buttons based on status
        if ($this->newStatus === 'ready') {
            $mail->action('View Pickup Details', route('orders.show', $this->order->id));
        } elseif ($this->newStatus === 'out_for_delivery') {
            $mail->action('Track Delivery', route('orders.track', $this->order->order_id));
        } elseif ($this->newStatus === 'delivered') {
            $mail->action('Rate Your Experience', route('orders.review', $this->order->id));
        }

        $mail->line('Thank you for choosing MediFind Medical Center!');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Order Status Update',
            'message' => 'Your order ' . $this->order->order_id . ' is now ' . ucfirst(str_replace('_', ' ', $this->newStatus)),
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_id,
            'medicine_name' => $this->order->medicine_name,
            'pharmacy_name' => $this->order->pharmacy_name,
            'previous_status' => $this->previousStatus,
            'new_status' => $this->newStatus,
            'total_amount' => $this->order->total_amount,
            'action_url' => route('orders.show', $this->order->id),
            'status_icon' => $this->getStatusIcon($this->newStatus),
            'status_color' => $this->getStatusColor($this->newStatus),
            'requires_action' => in_array($this->newStatus, ['ready', 'delivered']),
        ];
    }

    /**
     * Get status icon for UI display
     */
    private function getStatusIcon($status)
    {
        $icons = [
            'pending' => '⏳',
            'confirmed' => '✅',
            'preparing' => '🔄',
            'ready' => '📦',
            'out_for_delivery' => '🚚',
            'delivered' => '✅',
            'cancelled' => '❌',
            'refunded' => '💰'
        ];

        return $icons[$status] ?? '📋';
    }

    /**
     * Get status color for UI display
     */
    private function getStatusColor($status)
    {
        $colors = [
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'preparing' => 'orange',
            'ready' => 'green',
            'out_for_delivery' => 'purple',
            'delivered' => 'green',
            'cancelled' => 'red',
            'refunded' => 'gray'
        ];

        return $colors[$status] ?? 'gray';
    }
}
