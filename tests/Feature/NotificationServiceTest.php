<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Notifications\WelcomeNotification;
use App\Notifications\PolicyCreatedNotification;
use App\Notifications\PaymentConfirmationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected NotificationService $notificationService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->notificationService = app(NotificationService::class);
        Notification::fake();
    }

    public function test_can_send_welcome_notification(): void
    {
        $user = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
        ]);

        $result = $this->notificationService->sendWelcomeNotification($user);

        $this->assertTrue($result);
        Notification::assertSentTo($user, WelcomeNotification::class);
    }

    public function test_can_send_policy_created_notification(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $result = $this->notificationService->sendPolicyCreatedNotification($policy);

        $this->assertTrue($result);
        Notification::assertSentTo($user, PolicyCreatedNotification::class);
    }

    public function test_policy_notification_fails_without_user(): void
    {
        $policy = Policy::factory()->create(['customer_id' => null]);

        $result = $this->notificationService->sendPolicyCreatedNotification($policy);

        $this->assertFalse($result);
        Notification::assertNothingSent();
    }

    public function test_can_send_bulk_notifications(): void
    {
        $users = User::factory()->count(3)->create();
        $userIds = $users->pluck('id')->toArray();

        $results = $this->notificationService->sendBulkNotification(
            $userIds,
            WelcomeNotification::class,
            []
        );

        $this->assertEquals(3, $results['sent']);
        $this->assertEquals(0, $results['failed']);
        
        foreach ($users as $user) {
            Notification::assertSentTo($user, WelcomeNotification::class);
        }
    }

    public function test_bulk_notification_handles_invalid_user_ids(): void
    {
        $validUser = User::factory()->create();
        $userIds = [$validUser->id, 99999, 99998]; // Include invalid IDs

        $results = $this->notificationService->sendBulkNotification(
            $userIds,
            WelcomeNotification::class,
            []
        );

        $this->assertEquals(1, $results['sent']);
        $this->assertEquals(2, $results['failed']);
        $this->assertCount(2, $results['errors']);
    }
}