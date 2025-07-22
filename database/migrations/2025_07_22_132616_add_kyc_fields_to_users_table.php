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
            $table->string('ghana_card_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone_prefix')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_phone_country_code')->nullable();
            $table->string('company_address')->nullable();
            $table->string('current_position')->nullable();
            $table->string('employment_start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'ghana_card_number',
                'date_of_birth',
                'company_name',
                'company_email',
                'company_phone_prefix',
                'company_phone',
                'company_phone_country_code',
                'company_address',
                'current_position',
                'employment_start_date'
            ]);
        });
    }
};
