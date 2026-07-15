<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * team_id TIDAK dihapus (sengaja) — supaya data jadwal lama (kalau ada)
     * tetap tersimpan apa adanya untuk riwayat. Cuma dibikin nullable karena
     * mulai sekarang penjadwalan baru pakai assignment staff langsung
     * (tabel picket_schedule_user), bukan Team lagi.
     *
     * CATATAN: ->change() di bawah butuh package "doctrine/dbal".
     * Kalau migration ini error, jalankan dulu:
     *   composer require doctrine/dbal
     */
    public function up(): void
    {
        Schema::table('picket_schedules', function (Blueprint $table) {
            $table->foreignId('schedule_week_id')->nullable()->after('id')
                ->constrained('schedule_weeks')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::table('picket_schedules', function (Blueprint $table) {
            $table->dropForeign(['schedule_week_id']);
            $table->dropColumn('schedule_week_id');
        });
    }
};
