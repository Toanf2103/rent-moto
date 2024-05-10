<?php

use App\Enums\RentPackage\RentPackageStatus;
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
        Schema::create('rent_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('status', RentPackageStatus::getValues())->default(RentPackageStatus::INACTIVE);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_packages');
    }
};
