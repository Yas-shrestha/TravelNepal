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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('difficulty', ['easy', 'moderate', 'challenging', 'extreme']);
            $table->integer('duration_days');
            $table->integer('max_altitude_m')->nullable();
            $table->decimal('route_distance_km', 8, 2)->nullable();
            $table->integer('min_group_size');
            $table->integer('max_group_size');
            $table->string('best_season');
            $table->text('overview');
            $table->json('highlights');
            $table->string('cover_image');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_bike')->default(false);
            $table->boolean('bike_rental_available')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
