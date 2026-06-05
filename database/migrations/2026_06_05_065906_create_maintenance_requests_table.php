<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('landlord_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->enum('category', ['plumbing', 'electrical', 'structural', 'appliance', 'pest_control', 'cleaning', 'painting', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->decimal('estimated_cost', 12, 2)->nullable();
            $table->decimal('actual_cost', 12, 2)->nullable();
            $table->boolean('landlord_approved')->nullable();
            $table->text('images')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->integer('tenant_rating')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('category');
            $table->index(['property_id', 'status']);
            $table->index(['tenant_id', 'status']);
            $table->index(['assigned_to', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_requests');
    }
}
