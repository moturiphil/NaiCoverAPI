<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Policy;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Send welcome notification to a user
     */
    public function sendWelcome(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::findOrFail($request->user_id);
        $success = $this->notificationService->sendWelcomeNotification($user);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Welcome notification sent successfully' : 'Failed to send welcome notification',
            'data' => [
                'user_id' => $user->id,
                'email' => $user->email,
            ],
        ], $success ? 200 : 500);
    }

    /**
     * Send policy created notification
     */
    public function sendPolicyCreated(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'policy_id' => 'required|integer|exists:policies,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $policy = Policy::with('customer.user')->findOrFail($request->policy_id);
        $success = $this->notificationService->sendPolicyCreatedNotification($policy);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Policy notification sent successfully' : 'Failed to send policy notification',
            'data' => [
                'policy_id' => $policy->id,
                'customer_id' => $policy->customer_id,
                'user_email' => $policy->customer->user->email ?? null,
            ],
        ], $success ? 200 : 500);
    }

    /**
     * Send payment confirmation notification
     */
    public function sendPaymentConfirmation(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required|integer|exists:payments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payment = Payment::findOrFail($request->payment_id);
        $success = $this->notificationService->sendPaymentConfirmationNotification($payment);

        return response()->json([
            'success' => $success,
            'message' => $success ? 'Payment confirmation sent successfully' : 'Failed to send payment confirmation',
            'data' => [
                'payment_id' => $payment->id,
                'amount' => $payment->amount ?? 0,
            ],
        ], $success ? 200 : 500);
    }

    /**
     * Send bulk notifications
     */
    public function sendBulkNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'notification_type' => 'required|string|in:welcome',
            'data' => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $notificationClasses = [
            'welcome' => \App\Notifications\WelcomeNotification::class,
        ];

        $notificationClass = $notificationClasses[$request->notification_type];
        $results = $this->notificationService->sendBulkNotification(
            $request->user_ids,
            $notificationClass,
            $request->data ?? []
        );

        return response()->json([
            'success' => $results['sent'] > 0,
            'message' => "Bulk notification completed: {$results['sent']} sent, {$results['failed']} failed",
            'data' => $results,
        ]);
    }

    /**
     * Get notification history for a user
     */
    public function getNotificationHistory(Request $request, int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Notification history retrieved successfully',
            'data' => [
                'user_id' => $user->id,
                'notifications' => $notifications,
            ],
        ]);
    }
}
