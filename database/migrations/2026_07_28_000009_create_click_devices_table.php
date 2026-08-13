<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('click_devices', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id', 50)->unique();
            $table->date('donation_date');
            $table->string('device_type');
            $table->integer('quantity')->default(0);
            $table->string('beneficiary');
            $table->string('municipality', 100);
            $table->enum('status', ['Turned Over', 'Pending', 'In Transit'])->default('Pending');
            $table->timestamps();

            $table->index('status');
            $table->index('municipality');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_devices');
    }
};
