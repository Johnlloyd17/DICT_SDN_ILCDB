<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visit_code', 50)->unique();
            $table->foreignId('visitor_id')->constrained('visitors')->cascadeOnDelete();
            $table->foreignId('dtc_hub_id')->constrained('dtc_hubs')->cascadeOnDelete();
            $table->string('purpose_of_visit', 200)->nullable();
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable();
            $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
            $table->timestamps();

            $table->index('visitor_id');
            $table->index('dtc_hub_id');
            $table->index('status');
            $table->index('check_in_time');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};