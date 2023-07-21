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
            $table->id();
            $table->foreignId('approval_request_id')->constrained(
                table: 'approval_requests', indexName: 'approval_request_id'
            );
            $table->foreignId('user_id')->constrained(
                table: 'users', indexName: 'user_id_approved_request_approval'
            );
            $table->integer('status')->default(0);
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