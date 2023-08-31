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
        Schema::create('hearts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id');
            // $table->foreignId('user_id')->constrained(
            //     table: 'users', indexName: 'user_id_heart_forum'
            // );
            $table->string('forum_id');
            // $table->foreignId('forum_id')->constrained(
            //     table: 'forums', indexName: 'heart_belongto_forum'
            // );
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hearts');
    }
};