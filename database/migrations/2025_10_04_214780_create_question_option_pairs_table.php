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
        Schema::create('question_option_pairs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('left_option_id')->constrained('question_options')->onDelete('cascade');
            $table->foreignId('right_option_id')->constrained('question_options')->onDelete('cascade');
            $table->integer('xp')->default(0);
            $table->integer('coins')->default(0);
            $table->integer('marks')->default(0);
            $table->timestamps();

            // Ensure unique pairs
            $table->unique(['left_option_id', 'right_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_option_pairs');
    }
};
