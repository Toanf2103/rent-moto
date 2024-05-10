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
        Schema::create('moto_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('moto_id');
            $table->text('path');
            $table->timestamps();

            $table->foreign('moto_id')
                ->references('id')
                ->on('motos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moto_images');
    }
};
