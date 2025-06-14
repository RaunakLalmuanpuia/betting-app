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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // stores image filename or path
            $table->timestamp('bet_closes_at');
            $table->enum('status', ['open', 'closed', 'settled', 'cancelled'])->default('open');
            $table->foreignId('winning_option_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['status', 'bet_closes_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
