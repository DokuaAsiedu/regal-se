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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                ->nullable()
                ->constrained('payments')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->char('currency');
            $table->string('authorization_url');
            $table->string('reference')->unique();
            $table->string('gateway');
            $table->string('status')->nullable();
            $table->string('channel')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
