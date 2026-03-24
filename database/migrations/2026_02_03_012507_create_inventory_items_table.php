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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['onu', 'ont', 'router', 'modem', 'fiber_cable', 'switch', 'access_point', 'other']);
            $table->string('model')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->string('mac_address')->unique()->nullable();
            $table->enum('status', ['in_stock', 'assigned', 'deployed', 'maintenance', 'damaged', 'retired']);
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->string('supplier')->nullable();
            $table->string('location')->nullable();
            $table->date('warranty_expires')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
