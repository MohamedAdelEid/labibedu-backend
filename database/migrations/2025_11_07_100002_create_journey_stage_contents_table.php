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
        Schema::create('journey_stage_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained('journey_stages')->cascadeOnDelete();
            $table->enum('content_type', ['book', 'video', 'examTraining']);
            $table->unsignedBigInteger('content_id');
            $table->timestamps();

            $table->index(['stage_id', 'content_type', 'content_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journey_stage_contents');
    }
};

