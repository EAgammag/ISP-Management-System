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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference_number')->nullable()->after('transaction_id');
            $table->string('payment_method')->nullable()->after('method');
            $table->foreignId('invoice_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            $table->foreignId('customer_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['reference_number', 'payment_method', 'invoice_id', 'customer_id']);
        });
    }
};
