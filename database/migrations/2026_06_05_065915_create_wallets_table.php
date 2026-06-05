<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletsTable extends Migration
{
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->decimal('balance', 12, 2)->default(0);
            $table->decimal('total_earned', 14, 2)->default(0);
            $table->decimal('total_withdrawn', 14, 2)->default(0);
            $table->string('currency', 10)->default('TZS');
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallets');
    }
}
