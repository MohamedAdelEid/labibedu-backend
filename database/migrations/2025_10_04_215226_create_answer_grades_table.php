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
        Schema::create('answer_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade');
            $table->foreignId('graded_by')->nullable()->constrained('teachers')->onDelete('set null');
            $table->boolean('is_correct')->default(false);
            $table->integer('gained_xp')->default(0);
            $table->integer('gained_coins')->default(0);
            $table->integer('gained_marks')->default(0);
            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'graded'])->default('graded');
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            // Ensure one grade per answer
            $table->unique('answer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_grades');
    }
};
