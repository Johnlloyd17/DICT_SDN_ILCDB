<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('live_runs_completed')->default(0)->after('credentials');
            $table->integer('live_runs_total')->default(0)->after('live_runs_completed');
            $table->string('reference_folders', 255)->nullable()->after('live_runs_total');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['live_runs_completed', 'live_runs_total', 'reference_folders']);
        });
    }
};
