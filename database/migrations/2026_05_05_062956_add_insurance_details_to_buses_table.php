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
        Schema::table('buses', function (Blueprint $table) {
            $table->string('policy_number')->nullable()->after('license_expiry');
            $table->string('underwriter')->nullable()->after('policy_number');
            $table->string('coverage_type')->nullable()->after('underwriter');
            $table->string('emergency_number')->nullable()->after('coverage_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['policy_number', 'underwriter', 'coverage_type', 'emergency_number']);
        });
    }
};
