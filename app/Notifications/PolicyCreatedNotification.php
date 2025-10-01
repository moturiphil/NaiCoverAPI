<?php

namespace App\Notifications;

use App\Models\Policy;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PolicyCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Policy $policy;

    /**
     * Create a new notification instance.
     */
    public function __construct(Policy $policy)
    {
        $this->policy = $policy;
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
        $customerName = 'Valued Customer';
        if ($this->policy->customer && $this->policy->customer->user) {
            $user = $this->policy->customer->user;
            $customerName = trim($user->first_name.' '.$user->last_name) ?: 'Valued Customer';
        }

        $providerName = $this->policy->provider ? $this->policy->provider->name ?? 'Your Insurance Provider' : 'Your Insurance Provider';

        return (new MailMessage)
            ->subject('Your Insurance Policy Has Been Created')
            ->greeting("Hello {$customerName}!")
            ->line('Great news! Your insurance policy has been successfully created.')
            ->line("Policy ID: #{$this->policy->id}")
            ->line("Provider: {$providerName}")
            ->line('Created on: '.$this->policy->created_at->format('F j, Y'))
            ->action('View Policy Details', url("/policies/{$this->policy->id}"))
            ->line('Please keep this information for your records.')
            ->line('If you have any questions about your policy, please contact our customer service team.')
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
            'policy_id' => $this->policy->id,
            'customer_id' => $this->policy->customer_id,
            'provider_id' => $this->policy->provider ? $this->policy->provider->id : null,
            'notification_type' => 'policy_created',
            'created_at' => $this->policy->created_at,
        ];
    }
}
