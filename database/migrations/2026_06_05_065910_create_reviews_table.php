<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->nullable()->constrained('properties')->nullOnDelete();
            $table->foreignId('landlord_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('rating');
            $table->string('title')->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('flagged')->default(false);
            $table->text('flagged_reason')->nullable();
            $table->integer('helpful_count')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index('rating');
            $table->index('is_verified');
            $table->index(['property_id', 'rating']);
            $table->index(['landlord_id', 'rating']);
            $table->index(['reviewer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
