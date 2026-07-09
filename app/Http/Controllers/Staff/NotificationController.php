<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Riwayat catatan operan untuk jadwal-jadwal yang pernah/sedang ditugaskan
     * ke staff yang login (dicari lewat assignment staff langsung, bukan team_id).
     */
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::with(['picketSchedule.shift'])
            ->whereHas('picketSchedule.staff', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->latest()
            ->get();

        return view('staff.notifications.index', compact('notifications'));
    }

    /**
     * Menulis catatan operan baru untuk sebuah jadwal piket (biasanya jadwal hari ini).
     */
    public function store(Request $request)
    {
        $request->validate([
            'picket_schedule_id' => 'required|exists:picket_schedules,id',
            'message' => 'required|string',
        ]);

        Notification::create([
            'picket_schedule_id' => $request->picket_schedule_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return back()->with('success', 'Catatan operan berhasil dikirim untuk shift selanjutnya.');
    }

    /**
     * Menandai satu catatan operan sebagai sudah dibaca / selesai ditindaklanjuti.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return back()->with('success', 'Catatan operan ditandai selesai.');
    }
}
