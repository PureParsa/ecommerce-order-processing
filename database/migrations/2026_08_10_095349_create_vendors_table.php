<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->decimal('wallet_balance')->default(0);
            $table->boolean('payment_processing')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->timestamps();

            $table->index('payment_processing');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
