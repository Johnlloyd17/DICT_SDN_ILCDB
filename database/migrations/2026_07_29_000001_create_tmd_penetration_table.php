<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tmd_penetration', function (Blueprint $table) {
            $table->id();
            $table->string('municipality', 100)->unique();
            $table->integer('male')->default(0);
            $table->integer('female')->default(0);
            $table->integer('total')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tmd_penetration');
    }
};
