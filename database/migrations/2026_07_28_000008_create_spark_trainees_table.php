<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spark_trainees', function (Blueprint $table) {
            $table->id();
            $table->string('trainee_code', 50)->unique();
            $table->string('full_name');
            $table->string('specialty');
            $table->string('course');
            $table->string('municipality', 100);
            $table->string('employment_status', 100);
            $table->decimal('monthly_earnings', 10, 2)->nullable();
            $table->timestamps();

            $table->index('municipality');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spark_trainees');
    }
};
