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
        Schema::create('approval_request_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('approval_request_id');
            $table->string('approved_by_user_id');
            $table->enum('status', ['await_approved', 'not_approved', 'approved']);
            $table->dateTime('date_approved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_request_statuses');
    }
};