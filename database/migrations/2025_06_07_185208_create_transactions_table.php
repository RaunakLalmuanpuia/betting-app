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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bet_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['bet_placed', 'payment_received', 'payout_sent']);
            $table->decimal('amount', 12, 2);
            $table->string('reference')->nullable(); // external txn id, if any
            $table->text('remarks')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
            $table->index(['user_id', 'type', 'transaction_date']);
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
