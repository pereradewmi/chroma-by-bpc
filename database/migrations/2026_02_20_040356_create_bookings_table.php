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
            $table->string('title');
            $table->enum('type', ['event', 'session']);
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->integer('duration_hours')->nullable(); // For duration-based bookings
            $table->string('customer_name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->integer('number_of_people')->default(1);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('color')->default('#ffc107'); // Default yellow for pending
            $table->decimal('price', 10, 2)->nullable();
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
