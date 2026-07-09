<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PENTING: saya tidak punya isi file PicketSchedule.php yang asli (yang dikirim
 * cuma controller-nya), jadi ini REKONSTRUKSI berdasarkan pemakaian di
 * TeamController/ShiftController/PicketScheduleController/AbsenceController/
 * ReportController/NotificationController. Cek dulu apakah ada method/relasi
 * lain di file aslimu sebelum menimpanya dengan file ini — kalau ada,
 * gabungkan saja bagian yang baru (scheduleWeek(), staff()) ke file aslimu.
 */
class PicketSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_week_id',
        'date',
        'shift_id',
        'team_id', // dipertahankan untuk data lama (legacy), tidak dipakai di alur baru
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function scheduleWeek()
    {
        return $this->belongsTo(ScheduleWeek::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // Legacy: dipertahankan supaya data/relasi jadwal lama (sebelum redesign) tidak rusak.
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // BARU: staff yang ditugaskan langsung ke slot jadwal ini (menggantikan Team).
    public function staff()
    {
        return $this->belongsToMany(User::class, 'picket_schedule_user')->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
