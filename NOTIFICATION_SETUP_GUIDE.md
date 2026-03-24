# Client Notification System - Quick Setup Guide

## ✅ What's Been Implemented

### 1. Database
- ✅ Migration added notification preference columns to customers table
- ✅ Migration executed successfully

### 2. Backend Components
- ✅ **NotificationService** (`app/Services/NotificationService.php`)
  - Email notification sending
  - SMS notification sending (Semaphore API)
  - Billing reminders
  - Payment confirmations
  - Service activation notifications
  - Promotional offers

- ✅ **AccountController** updated with functional `updateNotifications()` method
- ✅ **BillingController** integrated with NotificationService
- ✅ **Customer Model** updated with notification preferences fields
- ✅ **SendBillingReminders Command** for automated reminders

### 3. Frontend
- ✅ Notification preferences form in Client Account page
- ✅ Toggle switches for each notification type
- ✅ Display of customer email and phone number
- ✅ Dynamic loading of current preferences from database

### 4. Routes
- ✅ Route already configured: `PUT /client/account/notifications`

## 🚀 Quick Start

### Step 1: Configure Email (Required)
Add to `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="ISP Management"
```

### Step 2: Configure SMS (Optional but Recommended)
Add to `.env`:
```env
SEMAPHORE_API_KEY=your_api_key_here
SEMAPHORE_SENDER_ID=ISP
```

Get your API key from: https://semaphore.co

### Step 3: Test the System

#### Test via Browser:
1. Login as a client
2. Go to Account Settings (`/client/account`)
3. Scroll to "Notification Preferences"
4. Toggle the switches and click "Save Preferences"
5. You should see: "Notification preferences updated successfully"

#### Test via Command Line:
```bash
# Test billing reminders
php artisan billing:send-reminders --days=3

# Test with Tinker
php artisan tinker
```

In Tinker:
```php
$customer = App\Models\Customer::first();
$service = app(\App\Services\NotificationService::class);

// Test email
$service->sendEmailNotification($customer, 'Test', 'Test message', 'general');

// Test SMS (requires phone number)
$service->sendSmsNotification($customer, 'Test SMS', 'general');

// Test billing reminder
$service->sendBillingReminder($customer, 1500, '2026-02-15');
```

### Step 4: Schedule Automated Reminders (Optional)
Add to `app/Console/Kernel.php` in the `schedule()` method:
```php
$schedule->command('billing:send-reminders --days=3')->dailyAt('09:00');
```

## 📋 Features Available

### For Clients:
1. **Manage Preferences** - Toggle each notification type on/off
2. **View Contact Info** - See where notifications will be sent
3. **Receive Notifications** for:
   - Billing reminders (email + SMS)
   - Payment confirmations (email + SMS)
   - Service activations (email + SMS)
   - Promotional offers (email + SMS)

### For Administrators:
1. **Automated Billing Reminders** - Send via artisan command
2. **Integration Points** - Easily add notifications to any event
3. **Logging** - All notifications logged in `storage/logs/laravel.log`
4. **Flexible** - Respects customer preferences automatically

## 🔧 How to Use in Your Code

### Send a Custom Notification:
```php
use App\Services\NotificationService;

$notificationService = app(NotificationService::class);

// Email only
$notificationService->sendEmailNotification(
    $customer,
    'Your subject',
    'Your HTML message',
    'general' // or 'billing', 'promotional'
);

// SMS only
$notificationService->sendSmsNotification(
    $customer,
    'Your SMS message',
    'general'
);

// Use built-in methods
$notificationService->sendBillingReminder($customer, 1500.00, '2026-02-15');
$notificationService->sendPaymentConfirmation($customer, 1500.00, 'REF123');
$notificationService->sendServiceActivation($customer, 'Premium Plan');
$notificationService->sendPromotionalOffer($customer, 'Title', 'Description');
```

### Check Customer Preferences:
```php
if ($customer->email_notifications) {
    // Customer wants email notifications
}

if ($customer->sms_notifications && $customer->phone) {
    // Customer wants SMS and has phone number
}

if ($customer->billing_reminders) {
    // Customer wants billing reminders
}
```

## 📊 Database Structure

**customers table** now includes:
- `email_notifications` (boolean, default: true)
- `sms_notifications` (boolean, default: true)
- `billing_reminders` (boolean, default: true)
- `promotional_offers` (boolean, default: false)

## 🎯 Current Implementation Status

| Feature | Status | Notes |
|---------|--------|-------|
| Database Migration | ✅ Complete | Columns added to customers table |
| Customer Model | ✅ Complete | Fillable fields and casts added |
| Notification Service | ✅ Complete | Email + SMS support |
| Account Controller | ✅ Complete | Preferences update functional |
| Billing Integration | ✅ Complete | Payment notifications work |
| Frontend View | ✅ Complete | Toggle switches working |
| Route Configuration | ✅ Complete | Already existed |
| SMS Gateway | ✅ Ready | Semaphore integration ready |
| Email System | ✅ Ready | Uses Laravel Mail |
| Billing Command | ✅ Complete | Artisan command created |
| Documentation | ✅ Complete | Full documentation provided |

## 📝 Next Steps (Optional Enhancements)

1. **Add to other events:**
   - Ticket responses
   - Service suspensions
   - Data usage alerts
   - Speed upgrades

2. **Create admin panel:**
   - Send bulk notifications
   - View notification history
   - Customize templates

3. **Add notification queue:**
   - Better performance for bulk sends
   - Retry failed notifications

4. **Create notification history table:**
   - Track all sent notifications
   - Allow customers to view history

## 🛠️ Troubleshooting

### Preferences not saving:
- Check browser console for errors
- Verify route exists: `php artisan route:list | grep notifications`
- Check logs: `tail storage/logs/laravel.log`

### Emails not sending:
- Test mail config: `php artisan tinker` → `Mail::raw('test', function($m) { $m->to('test@test.com')->subject('test'); })`
- Check `.env` mail settings
- Look in logs for errors

### SMS not sending:
- Verify Semaphore API key
- Check customer has phone number
- Ensure SMS credits available
- Check logs for API response

## 📞 Support

- Full documentation: `NOTIFICATION_SYSTEM_DOCUMENTATION.md`
- Configuration example: `.env.notification.example`
- Check logs: `storage/logs/laravel.log`

---

**System is now fully functional and ready to use!** 🎉
