<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade');
            $table->foreignId('vendor_id')
                ->constrained('vendors')
                ->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price');
            $table->enum('item_status', [
                'pending',
                'inventory_updated',
                'email_sent',
                'sms_sent',
                'completed',
                'failed'
            ])->default('pending');
            $table->string('last_failed_job')->nullable();
            $table->timestamps();

            $table->index('order_id');
            $table->index('vendor_id');
            $table->index('item_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
