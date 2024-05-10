<?php

use App\Enums\Moto\MotoStatus;
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
        Schema::create('motos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('license_plate', 30)->unique();
            $table->unsignedInteger('moto_type_id')->nullable();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->enum('status', MotoStatus::getValues())
                ->default(MotoStatus::ACTIVE);
            $table->double('price');
            $table->timestamps();

            $table->foreign('moto_type_id')
                ->references('id')
                ->on('moto_types')
                ->onUpdate('cascade')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motos');
    }
};
