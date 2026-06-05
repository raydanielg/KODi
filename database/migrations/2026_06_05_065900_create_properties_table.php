<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->enum('property_type', ['house', 'apartment', 'room', 'commercial', 'land']);
            $table->enum('status', ['available', 'rented', 'under_maintenance', 'unavailable'])->default('available');
            $table->decimal('price', 12, 2);
            $table->string('currency', 10)->default('TZS');
            $table->decimal('deposit', 12, 2)->nullable();
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->decimal('area_sqft', 10, 2)->nullable();
            $table->string('location_area');
            $table->string('location_city');
            $table->string('location_region');
            $table->text('location_address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('is_furnished')->default(false);
            $table->boolean('has_internet')->default(false);
            $table->boolean('has_water')->default(true);
            $table->boolean('has_electricity')->default(true);
            $table->boolean('has_parking')->default(false);
            $table->boolean('has_security')->default(false);
            $table->boolean('has_generator')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('property_type');
            $table->index('location_city');
            $table->index('location_region');
            $table->index(['status', 'property_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
