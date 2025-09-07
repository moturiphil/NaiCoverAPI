<?php

namespace App\Services;

use App\Models\User;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send welcome notification to new user
     */
    public function sendWelcomeNotification(User $user): bool
    {
        try {
            $user->notify(new \App\Notifications\WelcomeNotification($user));
            
            Log::info('Welcome notification sent', ['user_id' => $user->id, 'email' => $user->email]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welcome notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send policy created notification to customer
     */
    public function sendPolicyCreatedNotification(Policy $policy): bool
    {
        try {
            $customer = $policy->customer;
            if (!$customer || !$customer->user) {
                Log::warning('Policy notification skipped: no customer or user found', ['policy_id' => $policy->id]);
                return false;
            }

            $customer->user->notify(new \App\Notifications\PolicyCreatedNotification($policy));
            
            Log::info('Policy created notification sent', [
                'policy_id' => $policy->id,
                'customer_id' => $customer->id,
                'user_email' => $customer->user->email
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send policy created notification', [
                'policy_id' => $policy->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send payment confirmation notification
     */
    public function sendPaymentConfirmationNotification(Payment $payment): bool
    {
        try {
            // Assuming payment has a relationship to user or customer
            // We'll need to adapt this based on the actual Payment model structure
            $user = $this->getUserFromPayment($payment);
            
            if (!$user) {
                Log::warning('Payment confirmation skipped: no user found', ['payment_id' => $payment->id]);
                return false;
            }

            $user->notify(new \App\Notifications\PaymentConfirmationNotification($payment));
            
            Log::info('Payment confirmation notification sent', [
                'payment_id' => $payment->id,
                'user_email' => $user->email
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation notification', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send bulk notifications to multiple users
     */
    public function sendBulkNotification(array $userIds, $notificationClass, array $data = []): array
    {
        $results = ['sent' => 0, 'failed' => 0, 'errors' => []];

        foreach ($userIds as $userId) {
            try {
                $user = User::find($userId);
                if (!$user) {
                    $results['failed']++;
                    $results['errors'][] = "User not found: {$userId}";
                    continue;
                }

                $user->notify(new $notificationClass($data));
                $results['sent']++;
                
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Failed to notify user {$userId}: " . $e->getMessage();
            }
        }

        Log::info('Bulk notification completed', $results);
        return $results;
    }

    /**
     * Get user from payment (helper method)
     */
    private function getUserFromPayment(Payment $payment): ?User
    {
        try {
            // Get user through payment -> order -> customer -> user relationship
            if ($payment->order && $payment->order->customer && $payment->order->customer->user) {
                return $payment->order->customer->user;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Error getting user from payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}