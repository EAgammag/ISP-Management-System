# Notification System Documentation

## Overview
The notification system allows clients to manage their communication preferences and receive notifications via Email and SMS for various events such as billing reminders, payment confirmations, service activations, and promotional offers.

## Features

### 1. Notification Preferences Management
Clients can manage their notification preferences from the Account Settings page at `/client/account`.

#### Available Notification Types:
- **Email Notifications**: General account updates via email
- **SMS Notifications**: Important alerts via text message
- **Billing Reminders**: Payment due date reminders
- **Promotional Offers**: Special offers and promotions

### 2. Contact Information
Notifications are sent to:
- **Email**: Customer's email address (from `customers.email` field)
- **Phone**: Customer's phone number (from `customers.phone` field)

> ⚠️ **Note**: SMS notifications require a valid phone number. If the customer hasn't set their phone number, SMS notifications will be skipped.

## Database Schema

### Customer Table Additions
The following columns were added to the `customers` table:

```php
$table->boolean('email_notifications')->default(true);
$table->boolean('sms_notifications')->default(true);
$table->boolean('billing_reminders')->default(true);
$table->boolean('promotional_offers')->default(false);
```

**Migration File**: `2026_02_06_070000_add_notification_preferences_to_customers_table.php`

## Backend Implementation

### 1. NotificationService (`app/Services/NotificationService.php`)
The main service class that handles all notification sending logic.

#### Key Methods:

##### `sendEmailNotification($customer, $subject, $message, $type)`
Sends an email notification to the customer.
- Checks if email notifications are enabled
- Validates notification type preferences
- Logs all email sending attempts

##### `sendSmsNotification($customer, $message, $type)`
Sends an SMS notification to the customer.
- Checks if SMS notifications are enabled
- Validates phone number exists
- Integrates with Semaphore SMS API
- Logs all SMS sending attempts

##### `sendBillingReminder($customer, $amount, $dueDate)`
Sends billing reminder via both email and SMS.
- Only sends if `billing_reminders` is enabled
- Includes payment amount and due date

##### `sendPaymentConfirmation($customer, $amount, $referenceNumber)`
Sends payment confirmation notification.
- Sent after successful payment submission
- Includes amount and reference number

##### `sendServiceActivation($customer, $planName)`
Notifies customer when their service is activated.
- Sent when new service plan is activated

##### `sendPromotionalOffer($customer, $title, $description)`
Sends promotional offers to customers.
- Only sends if `promotional_offers` is enabled

### 2. AccountController Updates
**File**: `app/Http/Controllers/Client/AccountController.php`

#### `updateNotifications()` Method
Handles the updating of notification preferences:
```php
public function updateNotifications(Request $request)
{
    $customer = Auth::user()->customer;
    
    $customer->update([
        'email_notifications' => $request->has('email_notifications'),
        'sms_notifications' => $request->has('sms_notifications'),
        'billing_reminders' => $request->has('billing_reminders'),
        'promotional_offers' => $request->has('promotional_offers'),
    ]);
    
    return redirect()->route('client.account.index')
        ->with('success', 'Notification preferences updated successfully');
}
```

### 3. BillingController Integration
**File**: `app/Http/Controllers/Client/BillingController.php`

The payment submission method now sends notifications:
```php
public function submitPayment(Request $request)
{
    // ... payment creation logic ...
    
    // Send payment confirmation notification
    $notificationService = app(NotificationService::class);
    $notificationService->sendPaymentConfirmation(
        $customer, 
        $validated['amount'], 
        $validated['reference_number']
    );
    
    // ...
}
```

### 4. Billing Reminder Command
**File**: `app/Console/Commands/SendBillingReminders.php`

Artisan command to send automated billing reminders.

#### Usage:
```bash
# Send reminders for invoices due within 3 days
php artisan billing:send-reminders

# Send reminders for invoices due within 7 days
php artisan billing:send-reminders --days=7
```

#### Scheduling:
Add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    // Send billing reminders daily at 9 AM
    $schedule->command('billing:send-reminders --days=3')
             ->dailyAt('09:00');
}
```

## SMS Gateway Configuration

### Semaphore SMS API Integration
The system uses Semaphore SMS API for sending SMS messages in the Philippines.

#### Environment Configuration
Add the following to your `.env` file:

```env
SEMAPHORE_API_KEY=your_api_key_here
SEMAPHORE_SENDER_ID=ISP
```

#### Getting Semaphore API Key:
1. Sign up at https://semaphore.co
2. Go to Account → API
3. Copy your API key
4. Add it to your `.env` file

### Alternative SMS Providers
You can easily integrate other SMS providers by modifying the `sendSmsNotification()` method in `NotificationService.php`:

- **Twilio**: International SMS service
- **Nexmo/Vonage**: Global SMS gateway
- **MessageBird**: Multi-channel messaging
- **Local providers**: Integrate with local SMS gateways

## Frontend Implementation

### Notification Preferences Form
**File**: `resources/views/client/account/index.blade.php`

The notification preferences section displays:
1. Current contact information (email and phone)
2. Toggle switches for each notification type
3. Current preference states loaded from database

#### Features:
- Real-time toggle switches
- Displays customer's email and phone number
- Warning if phone number is not set
- Visual feedback for enabled/disabled preferences

## API Endpoints

### Update Notification Preferences
```
PUT /client/account/notifications
```

**Request Body:**
```
email_notifications: on/off (checkbox)
sms_notifications: on/off (checkbox)
billing_reminders: on/off (checkbox)
promotional_offers: on/off (checkbox)
```

**Response:**
Redirects to `/client/account` with success message.

## Email Configuration

Make sure your Laravel email configuration is properly set up in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Testing

### Test Notification Sending
You can test notifications using Laravel Tinker:

```bash
php artisan tinker
```

```php
// Get a customer
$customer = App\Models\Customer::first();

// Test email notification
$service = app(\App\Services\NotificationService::class);
$service->sendEmailNotification($customer, 'Test Subject', 'Test message', 'general');

// Test SMS notification
$service->sendSmsNotification($customer, 'Test SMS message', 'general');

// Test billing reminder
$service->sendBillingReminder($customer, 1500.00, '2026-02-15');

// Test payment confirmation
$service->sendPaymentConfirmation($customer, 1500.00, 'REF123456789');
```

### Manual Billing Reminder Test
```bash
php artisan billing:send-reminders --days=30
```

## Logging

All notification attempts are logged in `storage/logs/laravel.log`:

- **Info logs**: Successful notification sends
- **Warning logs**: Missing phone numbers or disabled preferences
- **Error logs**: Failed notification attempts

Example log entries:
```
[2026-02-06] local.INFO: Email notification sent to customer@example.com {"customer_id":1,"subject":"Payment Reminder","type":"billing"}
[2026-02-06] local.INFO: SMS notification sent to 09123456789 {"customer_id":1,"message":"ISP Payment Reminder...","type":"billing"}
[2026-02-06] local.ERROR: Failed to send email notification to customer@example.com {"customer_id":1,"error":"Connection timeout"}
```

## Security Considerations

1. **Rate Limiting**: SMS and email sending should be rate-limited to prevent abuse
2. **API Keys**: Store SMS API keys securely in `.env` file, never commit to version control
3. **Validation**: Phone numbers and email addresses are validated before sending
4. **Opt-out**: Customers can disable notifications at any time through their preferences

## Future Enhancements

Potential improvements for the notification system:

1. **Push Notifications**: Add browser push notifications
2. **WhatsApp Integration**: Send notifications via WhatsApp Business API
3. **Notification History**: Track all sent notifications in database
4. **Custom Templates**: Allow admins to customize notification templates
5. **A/B Testing**: Test different notification messages
6. **Notification Queue**: Queue notifications for better performance
7. **Unsubscribe Links**: One-click unsubscribe from specific notification types
8. **Multi-language Support**: Send notifications in customer's preferred language

## Troubleshooting

### Emails Not Sending
1. Check `.env` mail configuration
2. Verify SMTP credentials
3. Check `storage/logs/laravel.log` for errors
4. Test with `php artisan tinker` and `Mail::raw()`

### SMS Not Sending
1. Verify Semaphore API key in `.env`
2. Check customer has valid phone number
3. Ensure SMS notifications are enabled for customer
4. Check logs for API errors
5. Verify account has SMS credits

### Preferences Not Saving
1. Check route is defined: `Route::put('/account/notifications', ...)`
2. Verify CSRF token is present in form
3. Check controller method is called
4. Ensure database columns exist (run migration)

## Support

For issues or questions:
1. Check application logs: `storage/logs/laravel.log`
2. Review this documentation
3. Contact system administrator
