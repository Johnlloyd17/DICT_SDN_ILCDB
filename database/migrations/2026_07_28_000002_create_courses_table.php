<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 50)->unique();
            $table->string('title');
            $table->string('specialty_track', 100);
            $table->string('format_type', 100)->default('In-Person');
            $table->integer('duration_hours')->default(0);
            $table->json('credentials')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
