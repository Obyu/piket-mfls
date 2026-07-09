<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\PicketSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Pastikan memanggil Facade dari DomPDF
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['picketSchedule.shift', 'picketSchedule.staff', 'creator'])->latest()->get();
        return view('staff.reports.index', compact('reports'));
    }

    public function create()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Jadwal dicari lewat assignment staff langsung (bukan team_id lagi),
        // dan cuma minggu yang sudah di-publish yang valid untuk dipakai.
        $schedule = PicketSchedule::with(['shift', 'staff'])
            ->whereHas('scheduleWeek', fn ($q) => $q->where('status', 'published'))
            ->whereHas('staff', fn ($q) => $q->where('users.id', $user->id))
            ->where('date', $today)
            ->first();

        if (!$schedule) {
            return redirect()->route('staff.reports.index')->withErrors(['message' => 'Anda tidak memiliki jadwal piket hari ini.']);
        }

        return view('staff.reports.create', compact('schedule'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'picket_schedule_id' => 'required|exists:picket_schedules,id',
            'social_media_status' => 'nullable|array',
            'content_title' => 'nullable|string',
            'content_platform' => 'nullable|string',
            'content_status' => 'nullable|string',
            'total_leads_followed_up' => 'required|integer|min:0',
            'live_tiktok_duration_minutes' => 'required|integer|min:0',
            'live_tiktok_notes' => 'nullable|string',
            'issues_encountered' => 'required|string',
            'conclusion' => 'required|string',
            'documentation' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        $data = $request->except('documentation');
        $data['created_by_user_id'] = Auth::id();
        if ($request->hasFile('documentation')) {
            $path = $request->file('documentation')->store('reports', 'public');
            $data['documentation_path'] = $path;
        }

        Report::create($data);

        return redirect()->route('staff.reports.index')->with('success', 'Laporan akhir shift berhasil disubmit.');
    }

    public function exportPdf(Report $report)
    {
        $report->load(['picketSchedule.shift', 'picketSchedule.staff', 'creator']);
        $pdf = Pdf::loadView('staff.reports.pdf', compact('report'));

        // Nama file tidak lagi pakai nama Team (sudah tidak dipakai di penjadwalan),
        // dipakai nama Shift + tanggal sebagai gantinya.
        $fileName = 'Laporan_Piket_' . $report->picketSchedule->date . '_' . str_replace(' ', '_', $report->picketSchedule->shift->name) . '.pdf';

        return $pdf->download($fileName);
    }
}
