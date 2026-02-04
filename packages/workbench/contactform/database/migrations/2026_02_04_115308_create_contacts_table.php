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
    Schema::create('contacts', function (Blueprint $table) {
        $table->id();

        $table->string('fname', 255);
        $table->string('lname', 255);
        $table->string('company', 255)->nullable();
        $table->string('email', 255);
        $table->string('phone', 10);
        $table->text('message');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
