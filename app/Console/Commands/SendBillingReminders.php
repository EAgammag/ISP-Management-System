<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Customer;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendBillingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:send-reminders {--days=3 : Days before due date to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send billing reminders to customers with upcoming due invoices';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService)
    {
        $days = $this->option('days');
        $reminderDate = Carbon::now()->addDays($days)->format('Y-m-d');

        $this->info("Checking for invoices due on or before: {$reminderDate}");

        // Get unpaid invoices with due dates approaching
        $invoices = Invoice::where('status', 'unpaid')
            ->whereDate('due_date', '<=', $reminderDate)
            ->whereDate('due_date', '>=', Carbon::now()->format('Y-m-d'))
            ->with('customer')
            ->get();

        if ($invoices->isEmpty()) {
            $this->info('No invoices found for reminders.');
            return 0;
        }

        $this->info("Found {$invoices->count()} invoice(s) requiring reminders.");

        $successCount = 0;
        $failCount = 0;

        foreach ($invoices as $invoice) {
            try {
                $customer = $invoice->customer;
                
                if (!$customer) {
                    $this->warn("Skipping invoice #{$invoice->id}: No customer found");
                    $failCount++;
                    continue;
                }

                // Send billing reminder
                $notificationService->sendBillingReminder(
                    $customer,
                    $invoice->amount,
                    $invoice->due_date->format('F d, Y')
                );

                $this->line("✓ Reminder sent to {$customer->name} ({$customer->email})");
                $successCount++;

            } catch (\Exception $e) {
                $this->error("✗ Failed for invoice #{$invoice->id}: {$e->getMessage()}");
                $failCount++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  Success: {$successCount}");
        $this->info("  Failed: {$failCount}");

        return 0;
    }
}
