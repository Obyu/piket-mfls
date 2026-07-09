<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Notification;
use App\Models\PicketSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    // Halaman Dashboard Staff
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // ==========================================================
        // Jadwal hari ini kini dicari lewat assignment staff langsung
        // (tabel pivot picket_schedule_user), BUKAN lagi lewat team_id.
        // Hanya minggu yang sudah di-publish yang boleh muncul ke staff.
        // ==========================================================
        $schedule = PicketSchedule::with(['shift', 'staff', 'scheduleWeek'])
            ->whereHas('scheduleWeek', fn ($q) => $q->where('status', 'published'))
            ->whereHas('staff', fn ($q) => $q->where('users.id', $user->id))
            ->where('date', $today)
            ->first();

        $absence = null;
        $handoverNotifications = collect();

        if ($schedule) {
            // Cek apakah user sudah absen hari ini
            $absence = Absence::where('user_id', $user->id)
                        ->where('picket_schedule_id', $schedule->id)
                        ->first();

            // ==========================================================
            // NOTIFIKASI OPERAN (requirement bagian C):
            // Ambil catatan yang BELUM DIBACA dari shift SEBELUMNYA di
            // lokasi yang sama pada tanggal yang sama (mis. MNCU Shift 1 -> Shift 2).
            // ==========================================================
            $previousSchedule = PicketSchedule::with('shift')
                ->whereHas('scheduleWeek', fn ($q) => $q->where('status', 'published'))
                ->where('date', $schedule->date)
                ->whereHas('shift', function ($query) use ($schedule) {
                    $query->where('location', $schedule->shift->location)
                          ->where('end_time', '<=', $schedule->shift->start_time);
                })
                ->get()
                ->sortByDesc(fn ($s) => $s->shift->end_time)
                ->first();

            if ($previousSchedule) {
                $handoverNotifications = Notification::where('picket_schedule_id', $previousSchedule->id)
                    ->where('is_read', false)
                    ->latest()
                    ->get();
            }
        }

        return view('staff.dashboard', compact('schedule', 'absence', 'handoverNotifications'));
    }

    // Proses Check-In
    public function checkIn(Request $request)
    {
        $request->validate(['picket_schedule_id' => 'required|exists:picket_schedules,id']);

        Absence::create([
            'picket_schedule_id' => $request->picket_schedule_id,
            'user_id' => Auth::id(),
            'check_in_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Berhasil Check-In.');
    }

    // Proses Check-Out
    public function checkOut(Request $request, Absence $absence)
    {
        // Pastikan absensi milik user yang sedang login
        if ($absence->user_id !== Auth::id()) {
            abort(403);
        }

        $absence->update([
            'check_out_time' => Carbon::now(),
        ]);

        return back()->with('success', 'Berhasil Check-Out.');
    }
}
