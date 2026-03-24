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
        Schema::create('network_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['router', 'switch', 'access_point', 'onu', 'ont', 'modem']);
            $table->string('ip_address')->unique();
            $table->string('mac_address')->unique()->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['online', 'offline', 'maintenance', 'degraded'])->default('online');
            $table->integer('uptime')->default(0); // in seconds
            $table->decimal('cpu_usage', 5, 2)->nullable(); // percentage
            $table->decimal('memory_usage', 5, 2)->nullable(); // percentage
            $table->integer('connected_clients')->default(0);
            $table->timestamp('last_seen')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('network_devices');
    }
};
