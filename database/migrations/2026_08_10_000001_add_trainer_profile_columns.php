<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->string('accreditation')->nullable()->change();

            $table->string('designation')->nullable()->after('full_name');
            $table->string('agency')->nullable()->after('specialty');
            $table->string('contact')->nullable()->after('agency');
            $table->string('phone')->nullable()->after('contact');
            $table->unsignedInteger('courses')->default(0)->after('phone');
            $table->decimal('rating', 3, 2)->default(0)->after('courses');
        });
    }

    public function down(): void
    {
        Schema::table('trainers', function (Blueprint $table) {
            $table->dropColumn(['designation', 'agency', 'contact', 'phone', 'courses', 'rating']);
            $table->string('accreditation')->nullable(false)->change();
        });
    }
};
