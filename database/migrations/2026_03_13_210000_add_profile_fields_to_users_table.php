<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('driver_license_number')->nullable()->after('email');
            $table->date('insurance_policy_starts_at')->nullable()->after('driver_license_number');
            $table->date('insurance_policy_expires_at')->nullable()->after('insurance_policy_starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'driver_license_number',
                'insurance_policy_starts_at',
                'insurance_policy_expires_at',
            ]);
        });
    }
};
