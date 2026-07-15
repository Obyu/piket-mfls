<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Tambahan wajib untuk fitur redirect dashboard
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PicketScheduleController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Staff\AbsenceController;
use App\Http\Controllers\Staff\LeadController;
use App\Http\Controllers\Staff\ReportController;
use App\Http\Controllers\Staff\NotificationController;
use App\Models\PicketSchedule;
use App\Models\ScheduleWeek;
use App\Models\Shift;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// HANYA ADA SATU pembuka middleware auth & verified
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. RUTE REDIRECTOR (Solusi Error RouteNotFound)
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('staff.dashboard');
    })->name('dashboard');

    // Rute Global (Admin & Staff bisa akses Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==========================================
    // MODUL ADMIN (Sekretaris)
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            $data = [
                'total_shifts' => Shift::count(),
                'total_staff' => User::where('role', 'staff')->count(),
                'current_week' => ScheduleWeek::orderByDesc('week_start_date')->first(),
                'upcoming_schedules' => PicketSchedule::with(['staff', 'shift'])
                                        ->where('date', '>=', today())
                                        ->orderBy('date', 'asc')
                                        ->take(5)
                                        ->get()
            ];
            return view('admin.dashboard', $data);
        })->name('dashboard');

        Route::resource('shifts', ShiftController::class)->except(['create', 'show', 'edit']);

        // BARU: Manajemen Pengguna (Users) — requirement bagian B, belum ada sebelumnya.
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

        // ==========================================
        // JADWAL — REDESIGN TOTAL (grid mingguan, draft -> publish, assign staff langsung)
        // Route::resource lama untuk 'schedules' sudah tidak dipakai lagi.
        // ==========================================
        Route::get('/schedules', [PicketScheduleController::class, 'index'])->name('schedules.index');
        Route::post('/schedules/generate-week', [PicketScheduleController::class, 'generateWeek'])->name('schedules.generateWeek');
        Route::post('/schedules/{schedule}/assign', [PicketScheduleController::class, 'assignStaff'])->name('schedules.assign');
        Route::post('/schedules/week/{scheduleWeek}/publish', [PicketScheduleController::class, 'publishWeek'])->name('schedules.publishWeek');
        Route::post('/schedules/week/{scheduleWeek}/auto-fill', [PicketScheduleController::class, 'autoFill'])->name('schedules.autoFill');
        Route::delete('/schedules/{schedule}', [PicketScheduleController::class, 'destroy'])->name('schedules.destroy');
    });

    // ==========================================
    // MODUL STAFF (Anggota Piket)
    // ==========================================
    Route::middleware(['role:staff'])->prefix('staff')->name('staff.')->group(function () {
        // Rute dashboard staff yang duplikat sudah dihapus
        Route::get('/dashboard', [AbsenceController::class, 'index'])->name('dashboard');
        Route::post('/check-in', [AbsenceController::class, 'checkIn'])->name('checkin');
        Route::put('/check-out/{absence}', [AbsenceController::class, 'checkOut'])->name('checkout');

        Route::resource('leads', LeadController::class)->except(['show']);
        Route::resource('reports', ReportController::class)->only(['index', 'create', 'store']);
        Route::get('/reports/{report}/export', [ReportController::class, 'exportPdf'])->name('reports.export');

        // BARU: Notifikasi Operan — requirement bagian C, belum ada sebelumnya.
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications', [NotificationController::class, 'store'])->name('notifications.store');
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    });

}); // Penutup utama block middleware auth & verified

require __DIR__.'/auth.php';
