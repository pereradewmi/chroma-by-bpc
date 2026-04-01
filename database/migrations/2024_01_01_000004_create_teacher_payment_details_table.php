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
        Schema::create('teacherpaymentdetails', function (Blueprint $table) {
            $table->id('paymentID');
            $table->unsignedBigInteger('teacher_id');
            $table->decimal('amount', 10, 2);
            $table->string('month', 2);
            $table->timestamps();

            $table->foreign('teacher_id')->references('T_ID')->on('teacherdetails')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacherpaymentdetails');
    }
};
