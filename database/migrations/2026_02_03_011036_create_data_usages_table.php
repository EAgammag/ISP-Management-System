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
        Schema::create('data_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->decimal('data_used', 10, 2)->default(0); // in GB
            $table->decimal('data_uploaded', 10, 2)->default(0); // in GB
            $table->decimal('data_downloaded', 10, 2)->default(0); // in GB
            $table->integer('session_duration')->default(0); // in minutes
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->unique(['customer_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_usages');
    }
};
