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
            $table->dropColumn('number_date');
            $table->unsignedInteger('start');
            $table->unsignedInteger('end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_package_details', function (Blueprint $table) {
            $table->dropColumn('start');
            $table->dropColumn('end');
            $table->unsignedInteger('number_date');
        });
    }
};
