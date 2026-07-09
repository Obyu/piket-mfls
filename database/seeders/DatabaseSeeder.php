<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\Shift;
use App\Models\User;
use App\Models\PicketSchedule;
use App\Models\Absence;
use App\Models\Notification;
use App\Models\Lead;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Data Kelompok (Teams)
        $team1 = Team::create(['name' => 'Senin Shift 1']);
        $team2 = Team::create(['name' => 'Selasa Full Time']);

        // 2. Master Data Shift Operasional (Shifts)
        $shiftMncu = Shift::create([
            'name' => 'Shift Pagi MNCU',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'location' => 'mncu',
        ]);

        $shiftMks = Shift::create([
            'name' => 'Shift Full Time MKS',
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'location' => 'mks',
        ]);

        // 3. Data Pengguna (Users)
        // Admin
        User::create([
            'name' => 'Noval Adi (Sekretaris)',
            'email' => 'admin@mncu.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Staff 1
        $staff1 = User::create([
            'name' => 'Andi (Anggota Piket)',
            'email' => 'andi@mncu.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'team_id' => $team1->id,
        ]);

        // Staff 2
        $staff2 = User::create([
            'name' => 'Budi (Anggota Piket)',
            'email' => 'budi@mncu.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'team_id' => $team2->id,
        ]);

        // 4. Data Penjadwalan (Picket Schedules)
        $today = Carbon::today()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();

        $schedule1 = PicketSchedule::create([
            'date' => $today,
            'shift_id' => $shiftMncu->id,
            'team_id' => $team1->id,
        ]);

        $schedule2 = PicketSchedule::create([
            'date' => $tomorrow,
            'shift_id' => $shiftMks->id,
            'team_id' => $team2->id,
        ]);

        // 5. Data Absensi (Absences)
        // Simulasi Andi sudah Check-In dan Check-Out hari ini
        Absence::create([
            'picket_schedule_id' => $schedule1->id,
            'user_id' => $staff1->id,
            'check_in_time' => Carbon::today()->setHour(7)->setMinute(55),
            'check_out_time' => Carbon::today()->setHour(12)->setMinute(5),
        ]);

        // 6. Data Catatan Operan (Notifications)
        Notification::create([
            'picket_schedule_id' => $schedule1->id,
            'message' => 'Tolong lanjutkan follow-up 3 orang dari database Instagram, belum sempat terbalas di shift pagi.',
            'is_read' => false,
        ]);

        // 7. Data Prospek Pendaftar (Leads)
        Lead::create([
            'name' => 'Siti Aminah',
            'whatsapp_number' => '081234567890',
            'email' => 'siti@example.com',
            'major_interest' => 'Ilmu Komunikasi',
            'status' => 'new',
            'notes' => 'Tanya tentang beasiswa jalur prestasi.',
        ]);

        Lead::create([
            'name' => 'Joko Susilo',
            'whatsapp_number' => '081987654321',
            'email' => 'joko@example.com',
            'major_interest' => 'Manajemen Bisnis',
            'status' => 'interested',
            'notes' => 'Sudah diberikan link pendaftaran, akan diisi besok.',
        ]);

        Lead::create([
            'name' => 'Rina Melati',
            'whatsapp_number' => '085566778899',
            'status' => 'registered',
            'notes' => 'Sudah bayar biaya pendaftaran.',
        ]);

        // 8. Data Laporan Akhir Shift (Reports)
        Report::create([
            'picket_schedule_id' => $schedule1->id,
            'created_by_user_id' => $staff1->id,
            'social_media_status' => ['whatsapp' => true, 'instagram' => true],
            'content_title' => 'Beasiswa Kemerdekaan MNCU',
            'content_platform' => 'Instagram Reels',
            'content_status' => 'Published',
            'total_leads_followed_up' => 5,
            'live_tiktok_duration_minutes' => 45,
            'live_tiktok_notes' => 'Audiens ramai, banyak pertanyaan seputar biaya kuliah.',
            'issues_encountered' => 'Koneksi internet sempat terputus selama 10 menit saat Live TikTok.',
            'conclusion' => 'Shift berjalan lancar, target follow up tercapai dan konten berhasil tayang.',
            'documentation_path' => null, // Dikosongkan karena tidak ada file fisik dummy
        ]);
    }
}