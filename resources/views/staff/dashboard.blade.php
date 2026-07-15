<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-navy-800 leading-tight">
            {{ __('Dashboard Piket & Absensi') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ============================================================
             NOTIFIKASI OPERAN — requirement bagian C: "alert pop-up berisi
             catatan kendala/tugas yang belum selesai dari shift sebelumnya".
             Muncul otomatis tiap dashboard ini dimuat selama masih ada
             catatan yang belum ditandai selesai untuk shift hari ini.
             (Catatan: ini pop-up saat page-load, bukan real-time lewat
             websocket — untuk update tanpa refresh perlu Echo/Pusher.)
        ============================================================ --}}
        @if(($handoverNotifications ?? collect())->isNotEmpty())
            <div class="mb-6 space-y-3" x-data="{ show: true }" x-show="show" x-transition>
                @foreach($handoverNotifications as $note)
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-amber-800">Catatan Operan dari Shift Sebelumnya</p>
                            <p class="text-amber-700 mt-1">{{ $note->message }}</p>
                        </div>
                        <form action="{{ route('staff.notifications.read', $note->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-white text-amber-700 border border-amber-200 text-sm px-4 py-2 rounded-xl hover:bg-amber-100 transition whitespace-nowrap">
                                Tandai Selesai
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl p-6">
                <div class="flex justify-between items-center border-b border-slate-200/60 pb-2 mb-4">
                    <h3 class="text-lg font-semibold text-navy-800">Informasi Jadwal Hari Ini</h3>
                    @if($schedule)
                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'write-handover-modal')"
                                class="text-brand-400 bg-brand-300/10 hover:bg-brand-300/20 text-sm px-3 py-1.5 rounded-xl font-medium transition flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" clip-rule="evenodd" /></svg>
                            Tulis Operan
                        </button>
                    @endif
                </div>
                
                @if($schedule)
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Rekan Piket:</span>
                            <div class="flex items-center -space-x-2">
                                @forelse($schedule->staff as $mate)
                                    <div title="{{ $mate->name }}" class="w-7 h-7 rounded-full bg-navy-700/10 text-navy-700 border-2 border-white flex items-center justify-center text-xs font-semibold">
                                        {{ strtoupper(substr($mate->name, 0, 1)) }}
                                    </div>
                                @empty
                                    <span class="text-slate-400 text-sm">Belum ada petugas lain</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Shift Kerja:</span>
                            <span class="font-medium text-navy-900">{{ $schedule->shift->name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Waktu:</span>
                            <span class="font-medium text-brand-400 bg-brand-300/10 px-3 py-1 rounded-full text-sm">
                                {{ \Carbon\Carbon::parse($schedule->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->shift->end_time)->format('H:i') }} WIB
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Lokasi:</span>
                            <span class="font-medium uppercase tracking-wide {{ $schedule->shift->location === 'mncu' ? 'text-navy-700 bg-navy-700/10' : 'text-brand-500 bg-brand-300/10' }} px-3 py-1 rounded-full text-sm">
                                {{ $schedule->shift->location }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p>Anda tidak memiliki jadwal piket untuk hari ini.</p>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-slate-200/60 shadow-sm rounded-2xl p-6 flex flex-col justify-center items-center text-center">
                @if(!$schedule)
                    <p class="text-slate-500">Absensi tidak tersedia. Silakan hubungi Sekretaris jika ada kesalahan jadwal.</p>
                @else
                    @if(!$absence)
                        <div class="mb-4 text-slate-600">Silakan lakukan Check-In untuk memulai shift Anda.</div>
                        <form action="{{ route('staff.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="picket_schedule_id" value="{{ $schedule->id }}">
                            <button type="submit" class="bg-navy-700 hover:bg-navy-600 text-white font-semibold px-8 py-4 rounded-2xl shadow-md transition transform active:scale-95 text-lg w-full md:w-auto">
                                Check-In Sekarang
                            </button>
                        </form>
                    @elseif($absence && !$absence->check_out_time)
                        <div class="mb-2 text-emerald-600 font-medium bg-emerald-50 px-4 py-2 rounded-2xl inline-block">
                            Status: Sedang Bertugas
                        </div>
                        <p class="text-slate-500 mb-6">Waktu Check-In: <span class="font-semibold text-navy-800">{{ \Carbon\Carbon::parse($absence->check_in_time)->format('H:i') }} WIB</span></p>
                        
                        <form action="{{ route('staff.checkout', $absence->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" onclick="return confirm('Apakah Anda yakin shift sudah selesai dan ingin Check-Out?')" class="bg-rose-500 hover:bg-rose-600 text-white font-semibold px-8 py-4 rounded-2xl shadow-md transition transform active:scale-95 text-lg w-full md:w-auto">
                                Check-Out (Selesai Shift)
                            </button>
                        </form>
                    @else
                        <div class="text-navy-800">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-emerald-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-xl font-semibold mb-2">Shift Selesai!</h3>
                            <p class="text-slate-500">Terima kasih atas kerja keras Anda hari ini.</p>
                            <div class="mt-4 text-sm text-slate-600">
                                Check-In: {{ \Carbon\Carbon::parse($absence->check_in_time)->format('H:i') }} WIB<br>
                                Check-Out: {{ \Carbon\Carbon::parse($absence->check_out_time)->format('H:i') }} WIB
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    @if($schedule)
        <x-modal name="write-handover-modal" focusable>
            <form method="POST" action="{{ route('staff.notifications.store') }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-navy-900 mb-2">Tulis Catatan Operan</h2>
                <p class="text-sm text-slate-500 mb-4">Catatan ini akan tampil sebagai alert untuk kelompok yang bertugas di shift berikutnya, lokasi &amp; tanggal yang sama.</p>

                <input type="hidden" name="picket_schedule_id" value="{{ $schedule->id }}">
                <div class="bg-navy-50 text-navy-700 text-sm px-4 py-3 rounded-2xl mb-4">
                    Untuk jadwal: <strong>{{ $schedule->shift->name }}</strong>
                    &mdash; {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                </div>

                <div>
                    <label class="block text-sm font-medium text-navy-700">Pesan untuk Shift Selanjutnya</label>
                    <textarea name="message" rows="4" required placeholder="Contoh: Tolong lanjutkan follow up 5 leads baru dari DM Instagram..."
                        class="mt-1 block w-full bg-navy-50/50 border border-slate-200 text-navy-900 rounded-2xl focus:border-brand-300 focus:ring focus:ring-brand-200 focus:ring-opacity-50 transition shadow-sm"></textarea>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')"
                            class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2.5 bg-brand-300 text-navy-900 rounded-2xl hover:bg-brand-200 transition active:scale-95 font-medium shadow-sm">
                        Kirim Operan
                    </button>
                </div>
            </form>
        </x-modal>
    @endif
</x-app-layout>