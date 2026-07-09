<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * PENTING — soal penamaan:
 * Tabel ini bernama "notifications" sesuai dokumen requirement teknis (Halaman 4).
 * Laravel juga punya sistem notifikasi bawaan (trait Notifiable + database channel)
 * yang KALAU dipakai juga akan membuat tabel bernama "notifications" tapi dengan
 * struktur kolom yang beda (uuid, type, notifiable_type, dst).
 *
 * Kalau nanti kamu berniat pakai fitur notifikasi bawaan Laravel (mis. Auth::user()->notify(...)),
 * akan BENTROK dengan tabel ini. Kalau ragu, aman-nya ganti nama tabel & model ini jadi
 * "shift_handovers" / "ShiftHandover". Untuk sekarang saya ikuti persis nama di dokumen requirement.
 */
class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'picket_schedule_id',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function picketSchedule()
    {
        return $this->belongsTo(PicketSchedule::class);
    }
}
