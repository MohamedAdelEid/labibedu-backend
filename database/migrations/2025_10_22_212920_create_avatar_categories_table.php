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
        Schema::create('avatar_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_en')->unique(); // English name (labib, dinosaur, robot)
            $table->string('name_ar'); // Arabic name (لبيب، ديناصور، روبوت)
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avatar_categories');
    }
};
