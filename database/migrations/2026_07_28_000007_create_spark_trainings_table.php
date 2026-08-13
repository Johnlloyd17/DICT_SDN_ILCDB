<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spark_trainings', function (Blueprint $table) {
            $table->id();
            $table->string('track_id', 50)->unique();
            $table->string('specialization');
            $table->string('master_trainer');
            $table->integer('enrolled_count')->default(0);
            $table->decimal('budget_allocated', 12, 2)->default(0);
            $table->string('industry_partner');
            $table->enum('status', ['Upcoming', 'Ongoing', 'Completed'])->default('Upcoming');
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spark_trainings');
    }
};
