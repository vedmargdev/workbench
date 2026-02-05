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
    Schema::create('offline_test_marks', function (Blueprint $table) {
        $table->id();

        $table->uuid('uuid')->unique();

        $table->unsignedBigInteger('user_id')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();

        $table->string('session')->nullable();

        $table->unsignedBigInteger('student_id');

        $table->json('marks_data')->nullable();

        $table->text('deleted_reasons')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offline_test_marks');
    }
};
