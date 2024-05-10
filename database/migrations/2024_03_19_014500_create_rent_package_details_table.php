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
        Schema::create('rent_package_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rent_package_id');
            $table->unsignedInteger('percent');
            $table->unsignedInteger('number_date');
            $table->timestamps();

            $table->foreign('rent_package_id')
                ->references('id')
                ->on('rent_packages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_package_details');
    }
};
