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
            $table->string('service_type')->after('bus_id')->nullable();
            $table->decimal('offered_price', 10, 2)->after('destination')->nullable();
            $table->decimal('counter_price', 10, 2)->nullable()->after('offered_price');
            $table->string('status')->default('pending')->after('counter_price');
            $table->text('details')->nullable()->after('status');
            $table->date('return_date')->after('date')->nullable();
            $table->time('pickup_time')->after('date')->nullable();
            $table->time('return_time')->after('return_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'service_type', 'offered_price', 'counter_price', 
                'status', 'details', 'return_date', 
                'pickup_time', 'return_time'
            ]);
        });
    }
};
