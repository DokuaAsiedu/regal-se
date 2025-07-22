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
        Schema::create('kyc_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_phone_prefix');
            $table->string('customer_phone');
            $table->string('customer_phone_country_code');
            $table->string('customer_email');
            $table->string('customer_address');
            $table->string('customer_ghana_card_number');
            $table->date('customer_date_of_birth');
            $table->string('company_name');
            $table->string('customer_current_position');
            $table->string('company_phone_prefix');
            $table->string('company_phone');
            $table->string('company_phone_country_code');
            $table->string('company_address');
            $table->string('company_email');
            $table->string('customer_employment_start_date');
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_submissions');
    }
};
