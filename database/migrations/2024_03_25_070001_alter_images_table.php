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
        Schema::dropIfExists('moto_images');

        Schema::create('images', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('imageable');
            $table->text('path');
            $table->text('old_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
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

        Schema::dropIfExists('images');
    }
};
