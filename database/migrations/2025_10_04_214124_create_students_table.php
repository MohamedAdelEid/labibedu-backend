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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->foreignId('age_group_id')->nullable()->constrained('age_groups')->onDelete('set null');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->boolean('is_first_time')->default(true);
            $table->enum('language', ['ar', 'en'])->default('ar');
            $table->enum('theme', ['minimal', 'blue', 'pink'])->default('minimal');
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('haptic_feedback_enabled')->default(true);
            $table->integer('xp')->default(0);
            $table->integer('coins')->default(15);
            $table->date('date_of_birth')->nullable();
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};