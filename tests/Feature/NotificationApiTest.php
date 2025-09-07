<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Customer;
use App\Models\Policy;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Tests\TestCase;

class NotificationApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        Notification::fake();
        
        // Create an admin user for authentication
        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin'); // Assuming role is set up
        
        Passport::actingAs($this->adminUser);
    }

    public function test_can_send_welcome_notification_via_api(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/notifications/welcome', [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Welcome notification sent successfully',
            ]);
    }

    public function test_welcome_notification_api_validates_user_id(): void
    {
        $response = $this->postJson('/api/notifications/welcome', [
            'user_id' => 99999, // Non-existent user
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_can_send_policy_created_notification_via_api(): void
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create(['user_id' => $user->id]);
        $policy = Policy::factory()->create(['customer_id' => $customer->id]);

        $response = $this->postJson('/api/notifications/policy-created', [
            'policy_id' => $policy->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Policy notification sent successfully',
            ]);
    }

    public function test_can_send_bulk_notifications_via_api(): void
    {
        $users = User::factory()->count(3)->create();
        $userIds = $users->pluck('id')->toArray();

        $response = $this->postJson('/api/notifications/bulk', [
            'user_ids' => $userIds,
            'notification_type' => 'welcome',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'data' => [
                    'sent',
                    'failed',
                    'errors',
                ],
            ]);
    }

    public function test_can_get_notification_history_via_api(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/notifications/history/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Notification history retrieved successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    'user_id',
                    'notifications',
                ],
            ]);
    }

    public function test_notification_endpoints_require_authentication(): void
    {
        // Log out the authenticated user
        auth()->logout();

        $user = User::factory()->create();

        $response = $this->postJson('/api/notifications/welcome', [
            'user_id' => $user->id,
        ]);

        $response->assertStatus(401);
    }
}