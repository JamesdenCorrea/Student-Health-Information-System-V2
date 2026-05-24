<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dental_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tooth_code', 10);
            $table->string('condition')->default('healthy');
            $table->text('notes')->nullable();
            $table->date('recorded_at');
            $table->timestamps();

            $table->index(['student_id', 'tooth_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dental_records');
    }
};
