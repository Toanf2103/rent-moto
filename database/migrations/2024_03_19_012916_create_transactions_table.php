<?php

use App\Enums\Transaction\TransactionStatus;
use App\Enums\Transaction\TransactionType;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->enum('status', TransactionStatus::getValues())->default(TransactionStatus::UNPAID);
            $table->enum('type', TransactionType::getValues());
            $table->double('cost');
            $table->text('descriptions')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
