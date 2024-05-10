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
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('moto_id');
            $table->double('price');
            $table->unsignedInteger('employee_receive_id')->nullable();
            $table->text('employee_note')->nullable();
            $table->enum('type_pay', ['cash', 'banking'])->default('cash');
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('moto_id')
                ->references('id')
                ->on('motos')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('employee_receive_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
