<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Payment $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
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
        
        // Try to get customer name from notification target (User model)
        if (isset($notifiable->first_name, $notifiable->last_name)) {
            $customerName = trim($notifiable->first_name . ' ' . $notifiable->last_name) ?: 'Valued Customer';
        }

        // Format payment amount (assuming it's stored as cents or decimal)
        $amount = '$' . number_format($this->payment->amount ?? 0, 2);
        
        $paymentDate = $this->payment->created_at ? $this->payment->created_at->format('F j, Y g:i A') : 'Unknown';

        return (new MailMessage)
            ->subject('Payment Confirmation - InsureMore')
            ->greeting("Hello {$customerName}!")
            ->line('We have successfully received your payment.')
            ->line("Payment ID: #{$this->payment->id}")
            ->line("Amount: {$amount}")
            ->line("Payment Date: {$paymentDate}")
            ->line("Payment Method: " . ($this->payment->payment_method ?? 'Card'))
            ->action('View Payment Details', url("/payments/{$this->payment->id}"))
            ->line('Your payment has been processed and your account has been updated accordingly.')
            ->line('Thank you for your payment and for choosing InsureMore!')
            ->line('If you have any questions about this payment, please contact our customer service team.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id' => $this->payment->id,
            'amount' => $this->payment->amount ?? 0,
            'payment_method' => $this->payment->payment_method ?? 'Unknown',
            'notification_type' => 'payment_confirmation',
            'payment_date' => $this->payment->created_at,
        ];
    }
}