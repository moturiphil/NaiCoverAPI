<?php

/*
 * InsureMore API - Mailing Service Demo
 * 
 * This script demonstrates how to use the mailing service for various notification types.
 * Run this script via Artisan Tinker or as an Artisan command.
 */

use App\Models\User;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\Order;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Notifications\WelcomeNotification;
use App\Notifications\PolicyCreatedNotification;
use App\Notifications\PaymentConfirmationNotification;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class NotificationDemo
{
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = app(NotificationService::class);
    }

    /**
     * Demo 1: Send welcome notification to a new user
     */
    public function demoWelcomeNotification(): void
    {
        echo "=== Welcome Notification Demo ===\n";
        
        // Create a test user
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);
        
        echo "Created user: {$user->first_name} {$user->last_name} ({$user->email})\n";
        
        // Send welcome notification using the service
        $success = $this->notificationService->sendWelcomeNotification($user);
        
        echo $success ? "✓ Welcome notification sent successfully\n" : "✗ Failed to send welcome notification\n";
        
        // Alternative: Send directly using Laravel's notification system
        $user->notify(new WelcomeNotification($user));
        echo "✓ Direct notification sent\n";
        
        // Alternative: Send using Mailable
        Mail::to($user->email)->send(new WelcomeEmail($user));
        echo "✓ Mailable sent\n\n";
    }

    /**
     * Demo 2: Send policy created notification
     */
    public function demoPolicyNotification(): void
    {
        echo "=== Policy Created Notification Demo ===\n";
        
        // Create test data
        $user = User::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);
        
        echo "Created policy #{$policy->id} for {$user->first_name} {$user->last_name}\n";
        
        // Send notification
        $success = $this->notificationService->sendPolicyCreatedNotification($policy);
        
        echo $success ? "✓ Policy notification sent successfully\n\n" : "✗ Failed to send policy notification\n\n";
    }

    /**
     * Demo 3: Send payment confirmation notification
     */
    public function demoPaymentConfirmation(): void
    {
        echo "=== Payment Confirmation Demo ===\n";
        
        // Create test data
        $user = User::factory()->create(['first_name' => 'Bob', 'last_name' => 'Johnson']);
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $order = Order::factory()->paid()->create(['customer_id' => $customer->id]);
        $payment = Payment::factory()->successful()->create([
            'order_id' => $order->id,
            'amount' => 299.99,
            'method' => 'card'
        ]);
        
        echo "Created payment {$payment->payment_reference} for \${$payment->amount}\n";
        
        // Send notification
        $success = $this->notificationService->sendPaymentConfirmationNotification($payment);
        
        echo $success ? "✓ Payment confirmation sent successfully\n\n" : "✗ Failed to send payment confirmation\n\n";
    }

    /**
     * Demo 4: Send bulk notifications
     */
    public function demoBulkNotifications(): void
    {
        echo "=== Bulk Notifications Demo ===\n";
        
        // Create multiple users
        $users = User::factory()->count(5)->create();
        $userIds = $users->pluck('id')->toArray();
        
        echo "Created " . count($userIds) . " users for bulk notification\n";
        
        // Send bulk welcome notifications
        $results = $this->notificationService->sendBulkNotification(
            $userIds,
            WelcomeNotification::class
        );
        
        echo "Bulk notification results: {$results['sent']} sent, {$results['failed']} failed\n";
        
        if (!empty($results['errors'])) {
            echo "Errors:\n";
            foreach ($results['errors'] as $error) {
                echo "  - {$error}\n";
            }
        }
        echo "\n";
    }

    /**
     * Demo 5: Check notification history
     */
    public function demoNotificationHistory(): void
    {
        echo "=== Notification History Demo ===\n";
        
        // Get a user with notifications
        $user = User::first();
        if (!$user) {
            echo "No users found. Run other demos first.\n\n";
            return;
        }
        
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->take(5)->get();
        
        echo "Recent notifications for {$user->email}:\n";
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            echo "  - {$notification->type} at {$notification->created_at} " . 
                 ($notification->read_at ? "(read)" : "(unread)") . "\n";
        }
        echo "\n";
    }

    /**
     * Run all demos
     */
    public function runAllDemos(): void
    {
        echo "InsureMore API - Mailing Service Demo\n";
        echo "====================================\n\n";
        
        $this->demoWelcomeNotification();
        $this->demoPolicyNotification();
        $this->demoPaymentConfirmation();
        $this->demoBulkNotifications();
        $this->demoNotificationHistory();
        
        echo "Demo completed! Check your Mailtrap inbox for emails.\n";
    }
}

// Usage examples:
// 
// In Artisan Tinker (php artisan tinker):
// $demo = new NotificationDemo();
// $demo->runAllDemos();
// 
// Or run individual demos:
// $demo->demoWelcomeNotification();
// $demo->demoPolicyNotification();