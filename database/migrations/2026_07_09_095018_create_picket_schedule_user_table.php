<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PENTING: jalankan migration ini SETELAH tabel picket_schedules ada
     * (dan setelah migration schedule_weeks di atasnya).
     */
    public function up(): void
    {
        Schema::create('picket_schedule_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picket_schedule_id')->constrained('picket_schedules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['picket_schedule_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('picket_schedule_user');
    }
};
