<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email_two_factor_code')->nullable()->after('password');
            $table->timestamp('email_two_factor_expires_at')->nullable()->after('email_two_factor_code');
            $table->timestamp('email_two_factor_sent_at')->nullable()->after('email_two_factor_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_two_factor_code',
                'email_two_factor_expires_at',
                'email_two_factor_sent_at',
            ]);
        });
    }
};
