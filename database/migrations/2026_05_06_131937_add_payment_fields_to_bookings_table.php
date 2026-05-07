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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('payer_phone', 20)->nullable()->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payer_phone');
            $table->string('mpesa_checkout_request_id')->nullable()->after('payment_reference');
            $table->string('mpesa_merchant_request_id')->nullable()->after('mpesa_checkout_request_id');
            $table->timestamp('paid_at')->nullable()->after('mpesa_merchant_request_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payer_phone',
                'payment_reference',
                'mpesa_checkout_request_id',
                'mpesa_merchant_request_id',
                'paid_at',
            ]);
        });
    }
};
