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
    Schema::create('offline_tests', function (Blueprint $table) {
        $table->id();

        $table->uuid('uuid')->unique();

        $table->unsignedBigInteger('user_id')->nullable();
        $table->unsignedBigInteger('created_by')->nullable();

        $table->string('session')->nullable();
        $table->string('test_name');

        $table->date('date')->nullable();
        $table->time('from_time')->nullable();
        $table->time('to_time')->nullable();

        $table->unsignedBigInteger('classes_id')->nullable();
        $table->string('section')->nullable();

        $table->json('sections_data')->nullable();
        $table->json('subject_data')->nullable();

        $table->text('syllabus')->nullable();

        $table->tinyInteger('status')->default(1);

        $table->text('restore_reasons')->nullable();
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
        Schema::dropIfExists('offline_tests');
    }
};
