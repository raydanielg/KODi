<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeasesTable extends Migration
{
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('landlord_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('lease_number')->unique();
            $table->enum('status', ['active', 'expired', 'terminated', 'renewed'])->default('active');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent_amount', 12, 2);
            $table->decimal('deposit_amount', 12, 2);
            $table->boolean('deposit_paid')->default(false);
            $table->enum('payment_frequency', ['monthly', 'quarterly', 'biannually', 'yearly'])->default('monthly');
            $table->integer('due_day')->default(5);
            $table->decimal('late_fee', 12, 2)->nullable();
            $table->decimal('penalty_percent', 5, 2)->default(0);
            $table->text('lease_document')->nullable();
            $table->integer('renewal_count')->default(0);
            $table->timestamp('terminated_at')->nullable();
            $table->text('termination_reason')->nullable();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('lease_number');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['property_id', 'status']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leases');
    }
}
