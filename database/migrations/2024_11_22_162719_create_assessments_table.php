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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('attendance_id')->constrained('attendances')->onDelete(action: 'cascade');
            $table->foreignId('assessment_schedule_id')->nullable()->constrained('assessment_schedules')->onDelete('cascade');
            $table->integer('score');
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->unique(['schedule_id', 'attendance_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
