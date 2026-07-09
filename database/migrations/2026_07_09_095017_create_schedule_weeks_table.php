<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedule_weeks', function (Blueprint $table) {
            $table->id();
            $table->date('week_start_date'); // Senin
            $table->date('week_end_date');   // Minggu
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique('week_start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedule_weeks');
    }
};
