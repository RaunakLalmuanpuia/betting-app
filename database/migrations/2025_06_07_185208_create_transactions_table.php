<?php

use App\Constants\PaymentStatus;
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
            $table->string('order_id')->unique();
            $table->string('transaction_id')->nullable();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bet_id')->nullable()->constrained()->nullOnDelete();

            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->string('reference')->nullable(); // external txn id, if any

            $table->string('status')->default(PaymentStatus::ATTEMPTED);
            $table->string('remark')->nullable();

            $table->text('msg')->nullable();
            $table->json('extra')->nullable();

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
