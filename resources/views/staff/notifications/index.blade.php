{{--
    Halaman BARU sesuai requirement bagian C "Notifikasi Operan".
    Alert pop-up real-time-nya sendiri sudah ditampilkan di staff/dashboard.blade.php
    (muncul otomatis saat ada catatan belum dibaca dari shift sebelumnya).
    Halaman ini adalah riwayat lengkapnya + form untuk menulis catatan baru.
--}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Notifikasi Operan') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-start mb-6">
            <p class="text-slate-500">Riwayat catatan operan antar-shift untuk kelompokmu. Untuk menulis catatan baru, buka <a href="{{ route('staff.dashboard') }}" class="text-sky-600 font-medium hover:underline">Dashboard</a> saat kamu sedang bertugas di jadwal piket hari ini.</p>
        </div>

        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($notifications as $notification)
                <div class="rounded-2xl border {{ $notification->is_read ? 'bg-white border-slate-100' : 'bg-amber-50 border-amber-100' }} p-5 shadow-sm border-l-4 {{ $notification->is_read ? 'border-l-slate-300' : 'border-l-amber-400' }} transition">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $notification->is_read ? 'bg-slate-100 text-slate-600' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">
                                    {{ $notification->picketSchedule->shift->name ?? 'Shift' }}
                                    <span class="text-slate-400 font-normal text-sm">&middot; {{ strtoupper($notification->picketSchedule->shift->location ?? '') }}</span>
                                </h3>
                                <span class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->translatedFormat('l, d M Y - H:i') }} WIB
                                </span>
                            </div>
                        </div>
                        @if($notification->is_read)
                            <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">Selesai</span>
                        @else
                            <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">Baru</span>
                        @endif
                    </div>

                    <p class="text-slate-700 mt-3 mb-3">{{ $notification->message }}</p>

                    @unless($notification->is_read)
                        <div class="flex justify-end">
                            <form action="{{ route('staff.notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-sm px-4 py-2 rounded-xl hover:bg-emerald-100 transition flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Tandai Selesai
                                </button>
                            </form>
                        </div>
                    @endunless
                </div>
            @empty
                <div class="bg-white border border-slate-100 rounded-2xl p-10 text-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Belum ada catatan operan untuk kelompokmu.
                </div>
            @endforelse
        </div>
    </div>

</x-app-layout>
