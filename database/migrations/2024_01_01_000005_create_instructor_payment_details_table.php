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
        Schema::create('instructorpaymentdetails', function (Blueprint $table) {
            $table->id('paymentID');
            $table->unsignedBigInteger('instructor_id');
            $table->decimal('amount', 10, 2);
            $table->string('month', 2);
            $table->integer('sessions_count');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('instructor_id')->references('T_ID')->on('teacherdetails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructorpaymentdetails');
    }
};
