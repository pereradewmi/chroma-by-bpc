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
        if (!Schema::hasColumn('sessiondetails', 'status')) {
            Schema::table('sessiondetails', function (Blueprint $table) {
                $table->unsignedTinyInteger('status')->default(1)->after('sImage');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('sessiondetails', 'status')) {
            Schema::table('sessiondetails', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
