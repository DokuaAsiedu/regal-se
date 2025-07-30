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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->morphs('payable'); // creates `payable_type` (e.g. App\Models\Order) and `payable_id`
            $table->decimal('amount', 10, 2);
            $table->char('currency');
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('payment_channel')->nullable();
            $table->string('reference')->nullable();
            $table->string('transaction_id')->nullable();
            $table->json('payload')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
