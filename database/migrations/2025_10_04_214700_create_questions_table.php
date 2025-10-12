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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_training_id')->constrained('exams_trainings')->onDelete('cascade');
            $table->text('title');
            $table->enum('type', ['choice', 'true_false', 'connect', 'arrange' , 'written']);
            $table->integer('xp')->default(0);
            $table->integer('coins')->default(0);
            $table->integer('marks')->default(0);
            $table->string('language', 2)->default('ar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
