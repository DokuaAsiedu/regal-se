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
        Schema::table('users', function (Blueprint $table) {
            $table->text('phone_prefix')->nullable()->after('email_verified_at');
            $table->text('phone')->nullable()->after('phone_prefix');
            $table->text('phone_country_code')->nullable()->after('phone');
            $table->text('delivery_address')->nullable();
            $table->text('delivery_address_landmark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_prefix', 'phone', 'phone_country_code', 'delivery_address', 'delivery_address_landmark']);
        });
    }
};
