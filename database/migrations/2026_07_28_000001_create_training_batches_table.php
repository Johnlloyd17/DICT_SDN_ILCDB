<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_code', 50)->unique();
            $table->string('course_title');
            $table->string('venue');
            $table->integer('target_count')->default(0);
            $table->integer('enrolled_count')->default(0);
            $table->string('trainer_name');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('program', ['TMD', 'SPARK', 'CLICK']);
            $table->enum('status', ['Upcoming', 'Ongoing', 'Completed'])->default('Upcoming');
            $table->timestamps();

            $table->index('program');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_batches');
    }
};
