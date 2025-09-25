<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $userName = trim($this->user->first_name.' '.$this->user->last_name) ?: 'Valued Customer';

        return (new MailMessage)
            ->subject('Welcome to InsureMore!')
            ->greeting("Hello {$userName}!")
            ->line('Welcome to InsureMore, your trusted insurance partner.')
            ->line('We are excited to have you on board and look forward to providing you with the best insurance solutions.')
            ->line('Your account has been successfully created with the email: '.$this->user->email)
            ->action('Get Started', url('/dashboard'))
            ->line('If you have any questions, feel free to contact our support team.')
            ->line('Thank you for choosing InsureMore!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => trim($this->user->first_name.' '.$this->user->last_name),
            'user_email' => $this->user->email,
            'notification_type' => 'welcome',
        ];
    }
}
