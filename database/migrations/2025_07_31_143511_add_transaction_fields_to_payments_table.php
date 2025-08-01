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
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable()->change();
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->cascadeOnUpdate()
                ->nullOnDelete()
                ->change();
            $table->dropColumn(['reference', 'payload']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->string('transaction_id')->nullable()->change();
            $table->string('reference')->nullable();
            $table->json('payload')->nullable();
        });
    }
};
