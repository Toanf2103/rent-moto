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
            $table->renameColumn('start', 'rent_days_min');
            $table->renameColumn('end', 'rent_days_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rent_package_details', function (Blueprint $table) {
            $table->renameColumn('rent_days_min', 'start');
            $table->renameColumn('rent_days_max', 'end');
        });
    }
};
