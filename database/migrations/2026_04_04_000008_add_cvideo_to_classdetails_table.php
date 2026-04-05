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
        Schema::table('classdetails', function (Blueprint $table) {
            $table->string('cVideo')->nullable()->after('cImage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classdetails', function (Blueprint $table) {
            $table->dropColumn('cVideo');
        });
    }
};
