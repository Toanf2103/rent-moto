<?php

use App\Enums\User\UserRole;
use App\Enums\User\UserStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 100)->unique();
            $table->string('name', 100);
            $table->string('phone_number', 20)->nullable();
            $table->date('dob')->nullable();
            $table->enum('role', UserRole::getValues())->default(UserRole::USER);
            $table->text('avatar')->nullable();
            $table->enum('status', UserStatus::getValues())->default(UserStatus::REGISTER);
            $table->string('address', 100)->nullable();
            $table->text('verify_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
