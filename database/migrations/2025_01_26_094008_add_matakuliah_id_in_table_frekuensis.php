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
        Schema::table('frekuensis', callback: function (Blueprint $table) {
            $table->foreignId('mata_kuliah_id')
                ->nullable()
                ->constrained('mata_kuliahs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frekuensis', function (Blueprint $table) {
            $table->dropColumn('mata_kuliah_id');
        });
    }
};
