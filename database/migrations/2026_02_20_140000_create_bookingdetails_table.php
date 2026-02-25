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
        Schema::create('bookingdetails', function (Blueprint $table) {
            $table->id('booking_ID');
            $table->string('bName');
            $table->string('bEmail')->nullable();
            $table->string('bPhone');
            $table->date('booking_date');
            $table->datetime('bStart_datetime');
            $table->datetime('bEnd_datetime')->nullable();
            $table->string('bTitle');
            $table->text('bDescription')->nullable();
            $table->enum('bEvent_type', ['event', 'session']);
            $table->enum('bStatus', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('bPrice', 10, 2)->nullable();
            $table->enum('bPayment_status', ['pending', 'paid', 'refunded'])->default('pending');
            $table->integer('bApproved_by')->nullable();
            $table->datetime('bApproved_at')->nullable();
            $table->integer('bReject_by')->nullable();
            $table->datetime('bReject_at')->nullable();
            $table->text('bRejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookingdetails');
    }
};