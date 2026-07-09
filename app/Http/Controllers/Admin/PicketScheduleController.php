<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PicketSchedule;
use App\Models\ScheduleWeek;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PicketScheduleController extends Controller
{
    /**
     * Menampilkan grid jadwal 1 minggu (default: minggu paling baru / minggu ini).
     * Bisa pindah ke minggu lain (termasuk riwayat minggu yang sudah publish)
     * lewat query string ?week={id}.
     */
    public function index(Request $request)
    {
        $scheduleWeek = $request->filled('week')
            ? ScheduleWeek::findOrFail($request->week)
            : ScheduleWeek::orderByDesc('week_start_date')->first();

        $scheduleByDate = collect();

        if ($scheduleWeek) {
            $scheduleByDate = PicketSchedule::with(['shift', 'staff'])
                ->where('schedule_week_id', $scheduleWeek->id)
                ->get()
                ->groupBy(fn ($item) => $item->date->toDateString())
                ->map(fn ($group) => $group->sortBy(fn ($s) => $s->shift->start_time ?? ''));
        }

        // Untuk dropdown pindah minggu (riwayat + draft yang ada)
        $allWeeks = ScheduleWeek::orderByDesc('week_start_date')->get();

        // Untuk dropdown/search petugas di tiap slot
        $staffList = User::where('role', 'staff')->orderBy('name')->get();

        return view('admin.schedules.index', compact('scheduleWeek', 'scheduleByDate', 'allWeeks', 'staffList'));
    }

    /**
     * Generate draft jadwal untuk 1 minggu penuh (Senin-Minggu), otomatis
     * membuat 1 baris slot kosong untuk tiap kombinasi (hari x shift aktif).
     * Minggu yang sudah pernah dibuat sebelumnya TIDAK hilang — tetap ada
     * sebagai riwayat, cuma tidak ditimpa.
     */
    public function generateWeek(Request $request)
    {
        $request->validate([
            'week_start_date' => 'required|date',
        ]);

        $weekStart = Carbon::parse($request->week_start_date)->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $exists = ScheduleWeek::where('week_start_date', $weekStart->toDateString())->exists();

        if ($exists) {
            return back()->withErrors(['message' => 'Draft/jadwal untuk minggu tersebut sudah pernah dibuat.']);
        }

        $scheduleWeek = ScheduleWeek::create([
            'week_start_date' => $weekStart->toDateString(),
            'week_end_date' => $weekEnd->toDateString(),
            'status' => 'draft',
        ]);

        $shifts = Shift::all();

        for ($date = $weekStart->copy(); $date->lte($weekEnd); $date->addDay()) {
            foreach ($shifts as $shift) {
                PicketSchedule::create([
                    'schedule_week_id' => $scheduleWeek->id,
                    'date' => $date->toDateString(),
                    'shift_id' => $shift->id,
                ]);
            }
        }

        return redirect()->route('admin.schedules.index', ['week' => $scheduleWeek->id])
            ->with('success', 'Draft jadwal untuk minggu ' . $weekStart->translatedFormat('d M') . ' - ' . $weekEnd->translatedFormat('d M Y') . ' berhasil dibuat. Silakan isi petugas per slot.');
    }

    /**
     * Assign / update daftar staff yang bertugas pada satu slot jadwal
     * (satu tanggal + satu shift). Menggantikan pemasangan Team.
     */
    public function assignStaff(Request $request, PicketSchedule $schedule)
    {
        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $schedule->staff()->sync($request->user_ids ?? []);

        return back()->with('success', 'Petugas untuk slot ini berhasil diperbarui.');
    }

    /**
     * Publish minggu draft supaya jadwalnya baru muncul di dashboard/notifikasi staff.
     */
    public function publishWeek(ScheduleWeek $scheduleWeek)
    {
        $scheduleWeek->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return back()->with('success', 'Jadwal minggu ini berhasil dipublish dan sudah bisa dilihat staff.');
    }

    /**
     * Hapus satu slot (misal shift tambahan yang ternyata tidak dipakai di tanggal itu).
     * Bukan untuk hapus keseluruhan minggu.
     */
    public function destroy(PicketSchedule $schedule)
    {
        $schedule->delete();

        return back()->with('success', 'Slot jadwal berhasil dihapus.');
    }
}
