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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['data_booster', 'speed_upgrade', 'extra_service'])->default('data_booster');
            $table->decimal('data_amount', 10, 2)->nullable(); // in GB for data boosters
            $table->decimal('price', 10, 2);
            $table->integer('validity_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Pivot table for customer addons
        Schema::create('customer_addon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('addon_id')->constrained()->onDelete('cascade');
            $table->date('purchased_at');
            $table->date('expires_at');
            $table->enum('status', ['active', 'expired', 'used'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addon');
        Schema::dropIfExists('addons');
    }
};
