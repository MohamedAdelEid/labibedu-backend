<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('cover');
            $table->boolean('is_in_library')->default(false);
            $table->string('language', 2)->default('ar');
            $table->boolean('has_sound')->default(false);
            $table->integer('xp')->default(0);
            $table->integer('coins')->default(0);
            $table->integer('marks')->default(0);
            $table->foreignId('subject_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('related_training_id')->nullable()->constrained('exams_trainings')->nullOnDelete();

            $table->index(['is_in_library', 'subject_id', 'level_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
