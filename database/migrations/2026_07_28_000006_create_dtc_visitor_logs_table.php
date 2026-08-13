<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dtc_visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_code', 50)->unique();
            $table->string('visitor_name');
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->integer('age');
            $table->string('demographic_sector', 100);
            $table->foreignId('dtc_hub_id')->constrained('dtc_hubs')->cascadeOnDelete();
            $table->json('services_ailed');
            $table->string('session_duration', 50);
            $table->dateTime('visit_date');
            $table->timestamps();

            $table->index('dtc_hub_id');
            $table->index('visit_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dtc_visitor_logs');
    }
};
