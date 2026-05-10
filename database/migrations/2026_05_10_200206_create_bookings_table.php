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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rental_bike_id')->nullable()->constrained('bike_rentals')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('preferred_date');
            $table->integer('group_size');
            $table->text('message')->nullable();
            $table->boolean('has_own_bike')->nullable();
            $table->string('own_bike_model')->nullable();
            $table->decimal('rental_cost_usd', 10, 2)->nullable();
            $table->boolean('has_license')->nullable();
            $table->string('license_number')->nullable();
            $table->string('license_country')->nullable();
            $table->enum('license_type', ['local', 'international'])->nullable();
            $table->string('license_image')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
