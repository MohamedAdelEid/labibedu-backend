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
        Schema::create('journey_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('journey_levels')->cascadeOnDelete();
            $table->enum('type', ['book', 'video', 'examTraining']);
            $table->integer('order');
            $table->timestamps();

            $table->index(['level_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journey_stages');
    }
};

