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
        Schema::table('rent_package_details', function (Blueprint $table) {
            $table->dropColumn('rent_days_min');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_package_details', function (Blueprint $table) {
            $table->unsignedInteger('rent_days_min');
        });
    }
};
