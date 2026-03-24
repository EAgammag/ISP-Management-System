<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(true)->after('balance');
            $table->boolean('sms_notifications')->default(true)->after('email_notifications');
            $table->boolean('billing_reminders')->default(true)->after('sms_notifications');
            $table->boolean('promotional_offers')->default(false)->after('billing_reminders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications',
                'sms_notifications',
                'billing_reminders',
                'promotional_offers'
            ]);
        });
    }
};
