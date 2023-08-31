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
        Schema::create('approval_request_file_criterias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('file_url');

            $table->string('approval_request_id');
            // $table->foreignId('approval_request_id')->constrained(
                //     table: 'approval_requests', indexName: 'approval_request_id'
                // );
            $table->string('requirement_criteria_id');
            // $table->foreignId('requirement_criteria_id')->constrained(
            //     table: 'requirement_criterias', indexName: 'requirement_criteria_id'
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
        Schema::dropIfExists('approval_request_file_criterias');
    }
};