<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert;
use App\Mail\OrderConfirmation;
use App\Mail\NewOrderNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    public function sendLowStockAlert($availability)
    {
        $pharmacy = $availability->pharmacy;
        $medicines = $availability->medicine;

        // Send email notification to pharmacy admin
        $adminUsers = User::where('role', 'admin')->get();
        
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\LowStockAlert($availability));
        }

        // Send email to pharmacist
        if ($pharmacy->email) {
            Mail::to($pharmacy->email)->send(new LowStockAlert($availability));
        }

        return response()->json(['success' => true]);
    }

    public function sendOrderNotification($order)
    {
        // Send to customer
        $order->user->notify(new \App\Notifications\OrderConfirmation($order));
        
        // Send to pharmacy
        $pharmacy = $order->pharmacy;
        if ($pharmacy->email) {
            Mail::to($pharmacy->email)->send(new NewOrderNotification($order));
        }

        // Send to admin users
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new \App\Notifications\NewOrderReceived($order));
        }

        return response()->json(['success' => true]);
    }

    public function sendSecurityAlert($user, $type, $details)
    {
        $user->notify(new \App\Notifications\SecurityAlert($type, $details));

        // Send to all admin users for critical security events
        if (in_array($type, ['account_locked', 'suspicious_login', 'multiple_failed_attempts'])) {
            $adminUsers = User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $admin->notify(new \App\Notifications\SecurityAlert($type, $details, $user));
            }
        }

        return response()->json(['success' => true]);
    }

    public function createSystemNotification($type, $title, $message, $targetRole = null)
    {
        $users = User::query();
        
        if ($targetRole) {
            $users = $users->where('role', $targetRole);
        }

        $users->get()->each(function ($user) use ($type, $title, $message) {
            $user->notify(new \App\Notifications\SystemNotification($type, $title, $message));
        });

        return response()->json(['success' => true]);
    }

    public function getNotifications()
    {
        $notifications = Auth::user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'icon' => $this->getNotificationIcon($notification->type),
                    'color' => $this->getNotificationColor($notification->type)
                ];
            });

        return response()->json($notifications);
    }

    private function getNotificationIcon($type)
    {
        $icons = [
            'order' => 'fas fa-shopping-cart',
            'low_stock' => 'fas fa-exclamation-triangle',
            'security' => 'fas fa-shield-alt',
            'system' => 'fas fa-info-circle',
            'prescription' => 'fas fa-file-medical',
            'payment' => 'fas fa-credit-card'
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    private function getNotificationColor($type)
    {
        $colors = [
            'order' => 'blue',
            'low_stock' => 'yellow',
            'security' => 'red',
            'system' => 'green',
            'prescription' => 'purple',
            'payment' => 'indigo'
        ];

        return $colors[$type] ?? 'gray';
    }
}
