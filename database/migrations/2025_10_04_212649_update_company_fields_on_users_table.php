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
            $table->dropColumn([
                'company_name',
                'company_email',
                'company_phone_prefix',
                'company_phone',
                'company_phone_country_code',
                'company_address',
            ]);

            $table->string('staff_id')->nullable();
            $table->foreignId('company_id')
                ->after('date_of_birth')
                ->nullable()
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone_prefix')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_phone_country_code')->nullable();
            $table->string('company_address')->nullable();

            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'staff_id']);
        });
    }
};
