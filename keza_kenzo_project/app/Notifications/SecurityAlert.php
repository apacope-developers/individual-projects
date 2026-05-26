<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class SecurityAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $type;
    protected $details;
    protected $targetUser;

    public function __construct($type, $details, $targetUser = null)
    {
        $this->type = $type;
        $this->details = $details;
        $this->targetUser = $targetUser;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $subject = $this->getSubject();
        $greeting = $this->getGreeting($notifiable);
        $lines = $this->getAlertLines();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->lines($lines)
            ->action('View Security Settings', route('profile.security'))
            ->line('If this wasn\'t you, please secure your account immediately.')
            ->salutation('MediFind Security Team');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => 'security',
            'alert_type' => $this->type,
            'details' => $this->details,
            'target_user_id' => $this->targetUser ? $this->targetUser->id : null
        ];
    }

    private function getSubject()
    {
        $subjects = [
            'account_locked' => '🔒 Account Locked - Security Alert',
            'suspicious_login' => '⚠️ Suspicious Login Activity',
            'multiple_failed_attempts' => '🚨 Multiple Failed Login Attempts',
            'password_changed' => '🔐 Password Changed Successfully',
            '2fa_enabled' => '✅ Two-Factor Authentication Enabled',
            '2fa_disabled' => '⚠️ Two-Factor Authentication Disabled',
            'new_device' => '📱 New Device Login Detected'
        ];

        return $subjects[$this->type] ?? '🔔 Security Alert';
    }

    private function getGreeting($notifiable)
    {
        return 'Hello ' . $notifiable->name . ',';
    }

    private function getAlertLines()
    {
        $lines = [];

        switch ($this->type) {
            case 'account_locked':
                $lines[] = 'Your account has been temporarily locked due to multiple failed login attempts.';
                $lines[] = '**Lock Time:** ' . $this->details['locked_until'] ?? '30 minutes';
                $lines[] = '**Location:** ' . ($this->details['ip_address'] ?? 'Unknown');
                break;

            case 'suspicious_login':
                $lines[] = 'We detected a login from a new device or location.';
                $lines[] = '**IP Address:** ' . ($this->details['ip_address'] ?? 'Unknown');
                $lines[] = '**Location:** ' . ($this->details['location'] ?? 'Unknown');
                $lines[] = '**Device:** ' . ($this->details['user_agent'] ?? 'Unknown');
                $lines[] = '**Time:** ' . ($this->details['login_time'] ?? 'Unknown');
                break;

            case 'multiple_failed_attempts':
                $lines[] = 'We detected multiple failed login attempts on your account.';
                $lines[] = '**Attempts:** ' . ($this->details['attempts'] ?? 'Unknown');
                $lines[] = '**IP Address:** ' . ($this->details['ip_address'] ?? 'Unknown');
                $lines[] = '**Time:** ' . ($this->details['last_attempt'] ?? 'Unknown');
                break;

            case 'password_changed':
                $lines[] = 'Your password has been successfully changed.';
                $lines[] = '**Time:** ' . ($this->details['changed_at'] ?? 'Unknown');
                $lines[] = '**IP Address:** ' . ($this->details['ip_address'] ?? 'Unknown');
                break;

            case '2fa_enabled':
                $lines[] = 'Two-factor authentication has been enabled on your account.';
                $lines[] = 'Your account is now protected with an additional layer of security.';
                break;

            case '2fa_disabled':
                $lines[] = 'Two-factor authentication has been disabled on your account.';
                $lines[] = '**Time:** ' . ($this->details['disabled_at'] ?? 'Unknown');
                $lines[] = 'If this wasn\'t you, please enable 2FA immediately.';
                break;

            case 'new_device':
                $lines[] = 'A successful login was detected from a new device.';
                $lines[] = '**Device:** ' . ($this->details['user_agent'] ?? 'Unknown');
                $lines[] = '**IP Address:** ' . ($this->details['ip_address'] ?? 'Unknown');
                $lines[] = '**Time:** ' . ($this->details['login_time'] ?? 'Unknown');
                break;
        }

        return $lines;
    }

    private function getTitle()
    {
        $titles = [
            'account_locked' => 'Account Locked',
            'suspicious_login' => 'Suspicious Login Activity',
            'multiple_failed_attempts' => 'Multiple Failed Login Attempts',
            'password_changed' => 'Password Changed',
            '2fa_enabled' => '2FA Enabled',
            '2fa_disabled' => '2FA Disabled',
            'new_device' => 'New Device Login'
        ];

        return $titles[$this->type] ?? 'Security Alert';
    }

    private function getMessage()
    {
        $messages = [
            'account_locked' => 'Your account has been temporarily locked due to security concerns.',
            'suspicious_login' => 'We detected suspicious login activity on your account.',
            'multiple_failed_attempts' => 'Multiple failed login attempts were detected.',
            'password_changed' => 'Your account password has been changed successfully.',
            '2fa_enabled' => 'Two-factor authentication has been enabled for your account.',
            '2fa_disabled' => 'Two-factor authentication has been disabled for your account.',
            'new_device' => 'A login from a new device was detected.'
        ];

        return $messages[$this->type] ?? 'A security event occurred on your account.';
    }
}
