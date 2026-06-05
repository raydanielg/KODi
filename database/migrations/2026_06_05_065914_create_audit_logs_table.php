<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->integer('entity_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->index('created_at');
            $table->timestamps();

            $table->index('action');
            $table->index('entity_type');
            $table->index(['entity_type', 'entity_id']);
            $table->index(['user_id', 'action']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
