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
        Schema::table('teacherdetails', function (Blueprint $table) {
            $table->enum('teacher_type', ['class_teacher', 'instructor'])->default('class_teacher')->after('Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacherdetails', function (Blueprint $table) {
            $table->dropColumn('teacher_type');
        });
    }
};
