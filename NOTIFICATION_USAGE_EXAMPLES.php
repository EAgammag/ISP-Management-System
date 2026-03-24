<?php

/**
 * NOTIFICATION SYSTEM - USAGE EXAMPLES
 * 
 * This file demonstrates how to use the NotificationService
 * in various parts of your application.
 */

// ==================================================
// EXAMPLE 1: Sending Billing Reminder
// ==================================================

use App\Services\NotificationService;
use App\Models\Customer;

// In AdminBillingController or a scheduled job
public function sendReminder($customerId)
{
    $customer = Customer::findOrFail($customerId);
    $notificationService = app(NotificationService::class);
    
    // Send billing reminder (checks preferences automatically)
    $notificationService->sendBillingReminder(
        $customer,
        1500.00,  // Amount due
        '2026-02-15'  // Due date
    );
}

// ==================================================
// EXAMPLE 2: Payment Confirmation
// ==================================================

// In BillingController after payment is verified
public function confirmPayment($paymentId)
{
    $payment = Payment::findOrFail($paymentId);
    $customer = $payment->customer;
    
    $notificationService = app(NotificationService::class);
    
    // Update payment status
    $payment->update(['status' => 'confirmed']);
    
    // Send confirmation notification
    $notificationService->sendPaymentConfirmation(
        $customer,
        $payment->amount,
        $payment->reference_number
    );
}

// ==================================================
// EXAMPLE 3: Service Activation
// ==================================================

// In SubscriptionController when activating a service
public function activate($subscriptionId)
{
    $subscription = Subscription::findOrFail($subscriptionId);
    $customer = $subscription->customer;
    
    // Activate the subscription
    $subscription->update([
        'status' => 'active',
        'start_date' => now()
    ]);
    
    // Notify customer
    $notificationService = app(NotificationService::class);
    $notificationService->sendServiceActivation(
        $customer,
        $subscription->servicePlan->name
    );
}

// ==================================================
// EXAMPLE 4: Promotional Campaign
// ==================================================

// In MarketingController to send promotional offers
public function sendPromotion()
{
    $notificationService = app(NotificationService::class);
    
    // Get all customers who want promotional offers
    $customers = Customer::where('promotional_offers', true)->get();
    
    foreach ($customers as $customer) {
        $notificationService->sendPromotionalOffer(
            $customer,
            'Limited Time Offer: 50% Off Speed Upgrade!',
            'Upgrade your internet speed this month and get 50% off for the first 3 months!'
        );
    }
}

// ==================================================
// EXAMPLE 5: Custom Email Notification
// ==================================================

// Send a custom email notification
public function notifyCustomer($customerId)
{
    $customer = Customer::findOrFail($customerId);
    $notificationService = app(NotificationService::class);
    
    $subject = 'Service Maintenance Notice';
    $message = "
        <h2>Scheduled Maintenance</h2>
        <p>Dear {$customer->name},</p>
        <p>We will be performing scheduled maintenance on <strong>February 10, 2026</strong> from 2:00 AM to 4:00 AM.</p>
        <p>Your internet service may be temporarily interrupted during this time.</p>
        <p>We apologize for any inconvenience.</p>
    ";
    
    // Will only send if customer has email_notifications enabled
    $notificationService->sendEmailNotification(
        $customer,
        $subject,
        $message,
        'general'
    );
}

// ==================================================
// EXAMPLE 6: Custom SMS Notification
// ==================================================

// Send a custom SMS notification
public function sendUrgentAlert($customerId)
{
    $customer = Customer::findOrFail($customerId);
    $notificationService = app(NotificationService::class);
    
    $message = 'URGENT: Your account balance is low. Please pay to avoid service interruption.';
    
    // Will only send if customer has sms_notifications enabled
    $notificationService->sendSmsNotification(
        $customer,
        $message,
        'billing'
    );
}

// ==================================================
// EXAMPLE 7: Ticket Response Notification
// ==================================================

// In TicketController when admin replies to a ticket
public function addReply(Request $request, $ticketId)
{
    $ticket = Ticket::findOrFail($ticketId);
    $customer = $ticket->customer;
    
    // Add reply logic here...
    
    // Notify customer of new reply
    $notificationService = app(NotificationService::class);
    
    $emailSubject = "New Reply to Your Support Ticket #{$ticket->id}";
    $emailMessage = "
        <h2>Support Ticket Update</h2>
        <p>Dear {$customer->name},</p>
        <p>Your support ticket <strong>#{$ticket->id}</strong> has received a new reply.</p>
        <p><strong>Subject:</strong> {$ticket->subject}</p>
        <p>Please log in to your account to view the response.</p>
    ";
    
    $smsMessage = "Support ticket #{$ticket->id} has a new reply. Please check your account.";
    
    $notificationService->sendEmailNotification($customer, $emailSubject, $emailMessage, 'general');
    $notificationService->sendSmsNotification($customer, $smsMessage, 'general');
}

// ==================================================
// EXAMPLE 8: Data Usage Alert
// ==================================================

// In a scheduled job to check data usage
public function checkDataUsage()
{
    $notificationService = app(NotificationService::class);
    
    // Find customers exceeding 80% of their data cap
    $customers = Customer::whereHas('activeSubscription', function($query) {
        // Custom logic to find customers near data limit
    })->get();
    
    foreach ($customers as $customer) {
        $usage = $customer->getCurrentMonthUsage(); // Example method
        $limit = $customer->activeSubscription()->servicePlan->data_cap;
        $percentage = ($usage / $limit) * 100;
        
        if ($percentage >= 80) {
            $emailSubject = "Data Usage Alert - {$percentage}% Used";
            $emailMessage = "
                <h2>Data Usage Alert</h2>
                <p>Dear {$customer->name},</p>
                <p>You have used <strong>{$percentage}%</strong> of your monthly data allowance.</p>
                <p>Current usage: " . number_format($usage / 1024, 2) . " GB</p>
                <p>Data limit: " . number_format($limit / 1024, 2) . " GB</p>
            ";
            
            $smsMessage = "Data usage alert: You've used {$percentage}% of your monthly data allowance.";
            
            $notificationService->sendEmailNotification($customer, $emailSubject, $emailMessage, 'general');
            $notificationService->sendSmsNotification($customer, $smsMessage, 'general');
        }
    }
}

// ==================================================
// EXAMPLE 9: Service Suspension Warning
// ==================================================

// Send warning before suspending service
public function warnBeforeSuspension($customerId)
{
    $customer = Customer::findOrFail($customerId);
    $notificationService = app(NotificationService::class);
    
    $emailSubject = 'Service Suspension Warning';
    $emailMessage = "
        <h2 style='color: #DC2626;'>Service Suspension Warning</h2>
        <p>Dear {$customer->name},</p>
        <p>Your account has an outstanding balance of <strong>₱" . number_format($customer->balance, 2) . "</strong>.</p>
        <p>If payment is not received within 48 hours, your service will be suspended.</p>
        <p>Please settle your account immediately to avoid interruption.</p>
    ";
    
    $smsMessage = 'URGENT: Outstanding balance of ₱' . number_format($customer->balance, 2) . '. Pay within 48hrs to avoid service suspension.';
    
    $notificationService->sendEmailNotification($customer, $emailSubject, $emailMessage, 'billing');
    $notificationService->sendSmsNotification($customer, $smsMessage, 'billing');
}

// ==================================================
// EXAMPLE 10: Welcome Email for New Customers
// ==================================================

// In CustomerController after registration
public function sendWelcomeEmail($customerId)
{
    $customer = Customer::findOrFail($customerId);
    $notificationService = app(NotificationService::class);
    
    $emailSubject = 'Welcome to Our ISP Service!';
    $emailMessage = "
        <h2>Welcome, {$customer->name}!</h2>
        <p>Thank you for choosing our ISP service!</p>
        <p>Your account has been successfully created.</p>
        <p><strong>Account Details:</strong></p>
        <ul>
            <li>Email: {$customer->email}</li>
            <li>Phone: {$customer->phone}</li>
        </ul>
        <p>You can manage your account, view bills, and update preferences by logging into your account.</p>
        <p>If you have any questions, please don't hesitate to contact our support team.</p>
    ";
    
    $smsMessage = 'Welcome to our ISP service! Your account is now active. Login at our website to manage your account.';
    
    $notificationService->sendEmailNotification($customer, $emailSubject, $emailMessage, 'general');
    $notificationService->sendSmsNotification($customer, $smsMessage, 'general');
}

// ==================================================
// EXAMPLE 11: Bulk Notifications with Queue
// ==================================================

// For sending to many customers, use queues for better performance
use Illuminate\Support\Facades\Queue;

public function sendBulkNotification()
{
    $customers = Customer::where('email_notifications', true)->get();
    
    foreach ($customers as $customer) {
        // Dispatch to queue instead of sending immediately
        Queue::push(function($job) use ($customer) {
            $notificationService = app(NotificationService::class);
            $notificationService->sendEmailNotification(
                $customer,
                'Important Announcement',
                'Your message here',
                'general'
            );
            $job->delete();
        });
    }
}

// ==================================================
// EXAMPLE 12: Checking Preferences Before Sending
// ==================================================

public function sendConditionalNotification($customerId)
{
    $customer = Customer::findOrFail($customerId);
    
    // Manual check (not needed when using NotificationService)
    if ($customer->email_notifications && $customer->billing_reminders) {
        // Customer wants email notifications AND billing reminders
        $notificationService = app(NotificationService::class);
        $notificationService->sendBillingReminder($customer, 1500.00, '2026-02-15');
    }
    
    // Or let NotificationService handle it automatically
    $notificationService = app(NotificationService::class);
    $notificationService->sendBillingReminder($customer, 1500.00, '2026-02-15');
    // ^ This will automatically check preferences
}
