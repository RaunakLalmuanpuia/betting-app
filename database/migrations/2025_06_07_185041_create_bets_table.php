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
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_option_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->boolean('is_winner')->nullable(); // null = undecided
            $table->decimal('payout_amount', 12, 2)->nullable(); // payout to user
            $table->boolean('is_paid')->default(false); // payout completed?
            $table->timestamps();
            $table->softDeletes();

            $table->index(['event_id', 'event_option_id']);
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bets');
    }
};
