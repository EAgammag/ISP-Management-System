<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send email notification to customer
     * 
     * @param Customer $customer
     * @param string $subject
     * @param string $message
     * @param string $type Type of notification (billing, promotional, general)
     * @param array $attachments Array of attachments ['path' => 'file path', 'name' => 'file name', 'mime' => 'mime type']
     * @return bool
     */
    public function sendEmailNotification(Customer $customer, string $subject, string $message, string $type = 'general', array $attachments = []): bool
    {
        // Check if customer has email notifications enabled
        if (!$customer->email_notifications) {
            return false;
        }

        // Check specific notification types
        if ($type === 'billing' && !$customer->billing_reminders) {
            return false;
        }

        if ($type === 'promotional' && !$customer->promotional_offers) {
            return false;
        }

        try {
            Mail::send([], [], function ($mail) use ($customer, $subject, $message, $attachments) {
                $mail->to($customer->email)
                    ->subject($subject)
                    ->html($message);
                
                // Attach files if provided
                foreach ($attachments as $attachment) {
                    if (isset($attachment['data'])) {
                        // Attach from raw data
                        $mail->attachData(
                            $attachment['data'],
                            $attachment['name'],
                            ['mime' => $attachment['mime'] ?? 'application/pdf']
                        );
                    } elseif (isset($attachment['path']) && file_exists($attachment['path'])) {
                        // Attach from file path
                        $mail->attach(
                            $attachment['path'],
                            ['as' => $attachment['name'], 'mime' => $attachment['mime'] ?? 'application/pdf']
                        );
                    }
                }
            });

            Log::info("Email notification sent to {$customer->email}", [
                'customer_id' => $customer->id,
                'subject' => $subject,
                'type' => $type,
                'attachments' => count($attachments)
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email notification to {$customer->email}", [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send SMS notification to customer
     * 
     * @param Customer $customer
     * @param string $message
     * @param string $type Type of notification (billing, promotional, general)
     * @return bool
     */
    public function sendSmsNotification(Customer $customer, string $message, string $type = 'general'): bool
    {
        // Check if customer has SMS notifications enabled
        if (!$customer->sms_notifications) {
            return false;
        }

        // Check specific notification types
        if ($type === 'billing' && !$customer->billing_reminders) {
            return false;
        }

        if ($type === 'promotional' && !$customer->promotional_offers) {
            return false;
        }

        // Check if phone number exists
        if (empty($customer->phone)) {
            Log::warning("Cannot send SMS: No phone number for customer {$customer->id}");
            return false;
        }

        try {
            // SMS Gateway Integration
            // This is a placeholder for SMS gateway integration
            // You would integrate with services like Twilio, Semaphore, or other SMS providers
            
            // Example with Semaphore API (Philippines)
            $apiKey = config('services.semaphore.api_key');
            $senderId = config('services.semaphore.sender_id', 'ISP');
            
            if ($apiKey) {
                $this->sendViaSemaphore($customer->phone, $message, $apiKey, $senderId);
            }

            Log::info("SMS notification sent to {$customer->phone}", [
                'customer_id' => $customer->id,
                'message' => substr($message, 0, 50) . '...',
                'type' => $type
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send SMS notification to {$customer->phone}", [
                'customer_id' => $customer->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send SMS via Semaphore API
     * 
     * @param string $phoneNumber
     * @param string $message
     * @param string $apiKey
     * @param string $senderId
     * @return void
     */
    private function sendViaSemaphore(string $phoneNumber, string $message, string $apiKey, string $senderId): void
    {
        $ch = curl_init();
        
        $parameters = [
            'apikey' => $apiKey,
            'number' => $phoneNumber,
            'message' => $message,
            'sendername' => $senderId
        ];
        
        curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $output = curl_exec($ch);
        curl_close($ch);
        
        Log::debug("Semaphore API response", ['response' => $output]);
    }

    /**
     * Send billing reminder notification
     * 
     * @param Customer $customer
     * @param float $amount
     * @param string $dueDate
     * @return void
     */
    public function sendBillingReminder(Customer $customer, float $amount, string $dueDate): void
    {
        $emailSubject = "Payment Reminder - Amount Due: ₱" . number_format($amount, 2);
        $emailMessage = "
            <h2>Payment Reminder</h2>
            <p>Dear {$customer->name},</p>
            <p>This is a friendly reminder that your payment of <strong>₱" . number_format($amount, 2) . "</strong> is due on <strong>{$dueDate}</strong>.</p>
            <p>Please ensure payment is made on time to avoid service interruption.</p>
            <p>Thank you for your business!</p>
        ";

        $smsMessage = "ISP Payment Reminder: Amount due ₱" . number_format($amount, 2) . " on {$dueDate}. Please pay on time to avoid service interruption.";

        $this->sendEmailNotification($customer, $emailSubject, $emailMessage, 'billing');
        $this->sendSmsNotification($customer, $smsMessage, 'billing');
    }

    /**
     * Send service activation notification
     * 
     * @param Customer $customer
     * @param string $planName
     * @return void
     */
    public function sendServiceActivation(Customer $customer, string $planName): void
    {
        $emailSubject = "Service Activated - {$planName}";
        $emailMessage = "
            <h2>Service Activated</h2>
            <p>Dear {$customer->name},</p>
            <p>Your service plan <strong>{$planName}</strong> has been successfully activated!</p>
            <p>You can now enjoy your internet connection. If you have any questions, please don't hesitate to contact us.</p>
            <p>Thank you for choosing our service!</p>
        ";

        $smsMessage = "Your {$planName} service has been activated! Enjoy your internet connection. Thank you!";

        $this->sendEmailNotification($customer, $emailSubject, $emailMessage);
        $this->sendSmsNotification($customer, $smsMessage);
    }

    /**
     * Send payment confirmation notification
     * 
     * @param Customer $customer
     * @param float $amount
     * @param string $referenceNumber
     * @return void
     */
    public function sendPaymentConfirmation(Customer $customer, float $amount, string $referenceNumber): void
    {
        $emailSubject = "Payment Received - ₱" . number_format($amount, 2);
        $emailMessage = "
            <h2>Payment Confirmation</h2>
            <p>Dear {$customer->name},</p>
            <p>We have received your payment of <strong>₱" . number_format($amount, 2) . "</strong>.</p>
            <p>Reference Number: <strong>{$referenceNumber}</strong></p>
            <p>Thank you for your payment!</p>
        ";

        $smsMessage = "Payment received: ₱" . number_format($amount, 2) . ". Ref: {$referenceNumber}. Thank you!";

        $this->sendEmailNotification($customer, $emailSubject, $emailMessage, 'billing');
        $this->sendSmsNotification($customer, $smsMessage, 'billing');
    }

    /**
     * Send promotional notification
     * 
     * @param Customer $customer
     * @param string $title
     * @param string $description
     * @return void
     */
    public function sendPromotionalOffer(Customer $customer, string $title, string $description): void
    {
        $emailSubject = "Special Offer - {$title}";
        $emailMessage = "
            <h2>{$title}</h2>
            <p>Dear {$customer->name},</p>
            <p>{$description}</p>
            <p>Don't miss out on this limited time offer!</p>
        ";

        $smsMessage = "{$title} - {$description}";

        $this->sendEmailNotification($customer, $emailSubject, $emailMessage, 'promotional');
        $this->sendSmsNotification($customer, $smsMessage, 'promotional');
    }
}
