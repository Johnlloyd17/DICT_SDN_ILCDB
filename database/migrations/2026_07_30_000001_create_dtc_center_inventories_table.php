<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dtc_center_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('congressional_district', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('municipality_city', 100);
            $table->string('barangay', 100)->nullable();
            $table->string('center_name');
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->boolean('verified')->default(false);
            $table->date('moa_date_of_signing')->nullable();
            $table->date('date_of_launching')->nullable();
            $table->date('date_of_platform_registration')->nullable();
            $table->string('tcms_status', 50)->nullable();
            $table->string('tcms_key', 100)->nullable();
            $table->string('tcms_identifier', 100)->nullable();
            $table->string('tcms_verification_status', 50)->nullable();
            $table->string('odk_status', 50)->nullable();
            $table->string('connectivity_status', 50)->nullable();
            $table->string('type_of_center_host', 100)->nullable();
            $table->string('operational_status', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dtc_center_inventories');
    }
};
