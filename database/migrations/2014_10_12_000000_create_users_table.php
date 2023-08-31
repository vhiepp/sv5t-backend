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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('fullname');
            $table->string('slug')->unique();
            $table->string('sur_name')->nullable();
            $table->string('given_name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('slogan')->default('Tự hào, phấn đấu là Sinh Viên 5 Tốt');
            $table->string('class_id')->nullable();
            $table->string('stu_code')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->dateTime('date_of_birth')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('ttsv_id')->nullable();
            $table->enum('role', ['admin', 'student', 'leader'])->default('student');
            $table->longText('avatar');
            $table->rememberToken();
            $table->string('unit_id')->nullable();
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