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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('picket_schedule_id')->constrained('picket_schedules');
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->json('social_media_status')->nullable();
            $table->string('content_title')->nullable();
            $table->string('content_platform')->nullable();
            $table->string('content_status')->nullable();
            $table->integer('total_leads_followed_up')->default(0);
            $table->integer('live_tiktok_duration_minutes')->default(0);
            $table->text('live_tiktok_notes')->nullable();
            $table->text('issues_encountered')->nullable();
            $table->text('conclusion')->nullable();
            $table->string('documentation_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
