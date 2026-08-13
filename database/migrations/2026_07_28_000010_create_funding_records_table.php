<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funding_records', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_ref', 50)->unique();
            $table->enum('project', ['DWIA-TMD', 'DTC HUB', 'SPARK', 'PROJECT CLICK']);
            $table->text('description');
            $table->string('expense_category', 100);
            $table->decimal('allocated', 12, 2)->default(0);
            $table->decimal('obligated', 12, 2)->default(0);
            $table->decimal('disbursed', 12, 2)->default(0);
            $table->date('transaction_date');
            $table->enum('status', ['Disbursed', 'Obligated', 'Pending'])->default('Pending');
            $table->timestamps();

            $table->index('project');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funding_records');
    }
};
