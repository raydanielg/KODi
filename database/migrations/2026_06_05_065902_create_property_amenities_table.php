<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyAmenitiesTable extends Migration
{
    public function up()
    {
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('amenity_name');
            $table->string('amenity_icon')->nullable();
            $table->timestamps();

            $table->index(['property_id', 'amenity_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('property_amenities');
    }
}
