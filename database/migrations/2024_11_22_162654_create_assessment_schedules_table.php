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
        Schema::create('assessment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->unique()->constrained('schedules')->onDelete('cascade');
            $table->string('mata_kuliah');
            $table->string('frekuensi');
            $table->string('jadwal');
            $table->string('asisten');
            $table->foreignId('asisten_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('topik');
            $table->integer('nilai');
            $table->foreignId('approver_id')->nullable()->constrained('users', 'id')->onDelete('cascade');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_schedules');
    }
};
