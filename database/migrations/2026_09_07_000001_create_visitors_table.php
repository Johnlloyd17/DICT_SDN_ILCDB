<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_number', 50)->nullable();
            $table->enum('gender', ['Male', 'Female'])->default('Male');
            $table->integer('age');
            $table->string('demographic_sector', 100);
            $table->timestamps();

            $table->index('name');
            $table->index('gender');
            $table->index('demographic_sector');
            $table->unique(['name', 'contact_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};