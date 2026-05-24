<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinic_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->dateTime('visited_at');
            $table->text('complaint');
            $table->text('assessment')->nullable();
            $table->text('treatment')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->string('blood_pressure')->nullable();
            $table->unsignedSmallInteger('pulse_rate')->nullable();
            $table->string('nurse_name')->nullable();
            $table->string('disposition')->nullable();
            $table->dateTime('follow_up_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_visits');
    }
};
