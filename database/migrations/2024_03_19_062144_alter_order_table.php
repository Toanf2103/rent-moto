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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_rent_package_id_foreign');
            $table->dropColumn('rent_package_id');
            $table->unsignedInteger('rent_package_detail_id')->nullable();
            $table->foreign('rent_package_detail_id')
                ->references('id')
                ->on('rent_package_details')
                ->onUpdate('cascade')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('rent_package_detail_id');
            $table->dropForeign('orders_rent_package_details_id_foreign');
            $table->unsignedInteger('rent_package_id')->nullable();
            $table->foreign('rent_package_id')
                ->references('id')
                ->on('rent_packages')
                ->onUpdate('cascade')
                ->nullOnDelete();
        });
    }
};
