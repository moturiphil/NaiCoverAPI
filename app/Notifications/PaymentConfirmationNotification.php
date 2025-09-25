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
            $customerName = trim($notifiable->first_name.' '.$notifiable->last_name) ?: 'Valued Customer';
        }

        // Format payment amount
        $amount = '$'.number_format($this->payment->amount, 2);

        $paymentDate = $this->payment->paid_at ?
            $this->payment->paid_at->format('F j, Y g:i A') :
            $this->payment->created_at->format('F j, Y g:i A');

        return (new MailMessage)
            ->subject('Payment Confirmation - InsureMore')
            ->greeting("Hello {$customerName}!")
            ->line('We have successfully received your payment.')
            ->line("Payment Reference: {$this->payment->payment_reference}")
            ->line("Amount: {$amount}")
            ->line("Payment Date: {$paymentDate}")
            ->line('Payment Method: '.ucfirst($this->payment->method))
            ->line('Status: '.ucfirst($this->payment->status))
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
            'payment_reference' => $this->payment->payment_reference,
            'amount' => $this->payment->amount,
            'payment_method' => $this->payment->method,
            'status' => $this->payment->status,
            'notification_type' => 'payment_confirmation',
            'payment_date' => $this->payment->paid_at ?? $this->payment->created_at,
        ];
    }
}
