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
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('icon')->nullable();
            $table->text('description')->nullable();

            $table->activeFields();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('amenitables', function (Blueprint $table) {
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->morphs('amenitable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenitables');
        Schema::dropIfExists('amenities');
    }
};
