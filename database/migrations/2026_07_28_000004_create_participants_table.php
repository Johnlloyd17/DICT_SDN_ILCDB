<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('participant_code', 50)->unique();
            $table->string('full_name');
            $table->foreignId('training_batch_id')->constrained('training_batches')->cascadeOnDelete();
            $table->string('agency_sector');
            $table->string('municipality', 100);
            $table->enum('completion_status', ['Completed', 'Ongoing', 'Pending'])->default('Pending');
            $table->date('completion_date')->nullable();
            $table->string('certificate_file')->nullable();
            $table->timestamps();

            $table->index('training_batch_id');
            $table->index('completion_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
