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
        Schema::create('exams_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_ar');
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable();
            $table->enum('type', ['exam', 'training']);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('duration')->nullable(); // in minutes, only for exams
            $table->timestamp('locked_after_duration')->nullable();
            $table->foreignId('created_by')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->onDelete('cascade');
            $table->foreignId('video_id')->nullable()->constrained('videos')->onDelete('set null');
            $table->foreignId('book_id')->nullable()->constrained('books')->onDelete('set null');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams_trainings');
    }
};
