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
        Schema::create('answer_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade');
            $table->foreignId('option_id')->constrained('question_options')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->integer('order')->nullable(); // for arrange type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_selections');
    }
};
