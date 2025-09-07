# InsureMore API - Mailing Service Documentation

## Overview

This document describes the mailing service implementation for the InsureMore API, which provides user notification functionality using Mailtrap for email delivery.

## Configuration

### Mailtrap Setup

1. Create a Mailtrap account at [mailtrap.io](https://mailtrap.io/)
2. Create a new inbox in your Mailtrap dashboard
3. Copy your SMTP credentials from the integration settings
4. Update your `.env` file with the following configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_FROM_ADDRESS="notifications@insuremore.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration

The notification system uses Laravel's queue system for better performance. Make sure you have configured your queue driver:

```env
QUEUE_CONNECTION=database
```

Run the queue worker:
```bash
php artisan queue:work
```

## Available Notifications

### 1. Welcome Notification
- **Purpose**: Sent to new users upon registration
- **Trigger**: New user account creation
- **Template**: HTML and text versions available

### 2. Policy Created Notification
- **Purpose**: Notifies customers when a new policy is created
- **Trigger**: New policy creation in the system
- **Template**: Uses Laravel's default notification template

### 3. Payment Confirmation Notification
- **Purpose**: Confirms successful payment processing
- **Trigger**: Payment completion
- **Template**: Uses Laravel's default notification template

## API Endpoints

All notification endpoints require authentication and admin role.

### Send Welcome Notification
```http
POST /api/notifications/welcome
Content-Type: application/json

{
    "user_id": 123
}
```

### Send Policy Created Notification
```http
POST /api/notifications/policy-created
Content-Type: application/json

{
    "policy_id": 456
}
```

### Send Payment Confirmation
```http
POST /api/notifications/payment-confirmation
Content-Type: application/json

{
    "payment_id": 789
}
```

### Send Bulk Notifications
```http
POST /api/notifications/bulk
Content-Type: application/json

{
    "user_ids": [1, 2, 3, 4, 5],
    "notification_type": "welcome",
    "data": {}
}
```

### Get Notification History
```http
GET /api/notifications/history/{userId}
```

## Usage Examples

### Programmatic Usage

```php
use App\Services\NotificationService;
use App\Models\User;

$notificationService = app(NotificationService::class);
$user = User::find(1);

// Send welcome notification
$success = $notificationService->sendWelcomeNotification($user);

// Send bulk notifications
$results = $notificationService->sendBulkNotification(
    [1, 2, 3], 
    \App\Notifications\WelcomeNotification::class
);
```

### Using Laravel's Built-in Notification System

```php
use App\Notifications\WelcomeNotification;
use App\Models\User;

$user = User::find(1);
$user->notify(new WelcomeNotification($user));
```

### Direct Mail Usage

```php
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

$user = User::find(1);
Mail::to($user->email)->send(new WelcomeEmail($user));
```

## Testing

Run the notification tests:

```bash
# Run specific notification tests
php artisan test tests/Feature/NotificationServiceTest.php
php artisan test tests/Feature/NotificationApiTest.php

# Run all tests
php artisan test
```

## Customization

### Adding New Notification Types

1. Create a new notification class:
```bash
php artisan make:notification CustomNotification
```

2. Implement the notification class with proper channels and content

3. Add the notification to the NotificationService

4. Create corresponding API endpoint if needed

### Customizing Email Templates

Email templates are located in `resources/views/emails/`. You can customize:
- HTML templates (`welcome.blade.php`)
- Text templates (`welcome-text.blade.php`)

## Monitoring and Logging

All notification activities are logged with appropriate log levels:
- **Info**: Successful notifications
- **Warning**: Skipped notifications (missing data)
- **Error**: Failed notifications

Check logs in `storage/logs/laravel.log` or your configured log destination.

## Troubleshooting

### Common Issues

1. **"Connection refused" error**: Check Mailtrap credentials and network connectivity
2. **"User not found" error**: Ensure user IDs exist in the database
3. **"Queue not processing"**: Make sure the queue worker is running
4. **Templates not found**: Verify email template files exist in `resources/views/emails/`

### Debug Mode

Enable debug mode in `.env` for detailed error messages:
```env
APP_DEBUG=true
MAIL_LOG_CHANNEL=single
```

## Security Considerations

- All API endpoints require authentication
- Sensitive data is logged securely
- Email templates are sanitized
- Rate limiting should be implemented for production use

## Production Deployment

For production deployment:

1. Replace Mailtrap with a production email service (SendGrid, AWS SES, etc.)
2. Configure proper queue workers with supervisor
3. Set up monitoring for failed notifications
4. Implement rate limiting for API endpoints
5. Configure proper logging and alerting

## Support

For issues and questions regarding the mailing service, please contact the development team or create an issue in the project repository.