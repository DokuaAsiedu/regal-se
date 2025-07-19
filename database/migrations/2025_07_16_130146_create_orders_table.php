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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->char('code')->unique();
            $table->text('customer_name'); // since login is not required to make a one time payment order this column and phone is needed to get in touch with the customer
            $table->text('customer_phone');
            $table->text('customer_email')->nullable();
            $table->text('delivery_address');
            $table->text('landmark');
            $table->text('delivery_note');
            $table->decimal('total_amount', 20, 2);
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->foreignId('status_id')
                ->nullable()
                ->constrained('status')
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
        Schema::dropIfExists('orders');
    }
};
