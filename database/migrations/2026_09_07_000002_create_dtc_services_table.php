<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dtc_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dtc_hub_id')->constrained('dtc_hubs')->cascadeOnDelete();
            $table->string('service_name', 100);
            $table->string('category', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('dtc_hub_id');
            $table->index('is_active');
            $table->unique(['dtc_hub_id', 'service_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dtc_services');
    }
};