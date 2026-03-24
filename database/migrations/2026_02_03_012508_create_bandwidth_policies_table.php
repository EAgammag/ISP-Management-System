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
        Schema::create('bandwidth_policies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('service_plan_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('download_speed'); // in Mbps
            $table->integer('upload_speed'); // in Mbps
            $table->integer('burst_speed')->nullable(); // in Mbps
            $table->integer('priority')->default(5); // QoS priority 1-10
            $table->string('contention_ratio')->default('1:1'); // e.g., 1:20
            $table->integer('data_cap')->nullable(); // in GB
            $table->enum('throttle_after_cap', ['yes', 'no'])->default('no');
            $table->integer('throttled_speed')->nullable(); // in Kbps
            $table->json('time_based_rules')->nullable(); // peak/off-peak hours
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bandwidth_policies');
    }
};
