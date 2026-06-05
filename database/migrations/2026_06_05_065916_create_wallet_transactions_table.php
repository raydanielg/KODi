<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['deposit', 'withdrawal', 'commission', 'payment', 'refund', 'payout', 'fee']);
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_type')->nullable();
            $table->integer('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->string('payment_method')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->index('created_at');
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index(['wallet_id', 'type']);
            $table->index(['user_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
            $table->index('transaction_reference');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
}
