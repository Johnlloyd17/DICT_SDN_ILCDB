<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visit_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('dtc_services')->cascadeOnDelete();
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index('visit_id');
            $table->index('service_id');
            $table->index('status');
            $table->unique(['visit_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visit_services');
    }
};