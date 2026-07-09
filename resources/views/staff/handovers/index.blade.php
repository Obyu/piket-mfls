{{--
    Halaman BARU — belum ada sebelumnya di project ini.
    Diadaptasi dari desain Stitch "notifikasi_operan_shiftmanager".

    Variabel yang diharapkan controller (silakan sesuaikan nama kolom dengan skema aslimu):
    - $handovers : koleksi catatan operan (id, message, created_at, is_read, team relasi->name, creator relasi->name)

    Route yang dipakai / diasumsikan (didaftarkan sendiri di web.php):
    - staff.handovers.index      GET   -> halaman ini
    - staff.handovers.store      POST  -> simpan catatan operan baru (dibungkus Route::has)
    - staff.handovers.read       PATCH -> tandai satu catatan selesai/dibaca (dibungkus Route::has, per-baris)

    Kalau route di atas belum ada, form & tombol tetap tampil tapi tidak akan submit kemana-mana
    (supaya file ini aman di-drop ke project tanpa langsung error).
--}}
@php
    $storeHandoverRoute = Route::has('staff.handovers.store') ? route('staff.handovers.store') : null;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Catatan Operan Shift') }}
            </h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-handover-modal')"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" clip-rule="evenodd" />
                </svg>
                Tulis Operan
            </button>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <p class="text-slate-500 mb-6">Pesan dan instruksi singkat dari shift sebelumnya untuk shift yang bertugas selanjutnya.</p>

        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($handovers ?? [] as $handover)
                @php
                    $isRead = $handover->is_read ?? false;
                    $markReadRoute = Route::has('staff.handovers.read') ? route('staff.handovers.read', $handover->id) : null;
                @endphp
                <div class="rounded-2xl border {{ $isRead ? 'bg-white border-slate-100' : 'bg-amber-50 border-amber-100' }} p-5 shadow-sm border-l-4 {{ $isRead ? 'border-l-slate-300' : 'border-l-amber-400' }} transition">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full {{ $isRead ? 'bg-slate-100 text-slate-600' : 'bg-amber-100 text-amber-700' }} flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">{{ $handover->team->name ?? 'Kelompok' }}</h3>
                                <span class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($handover->created_at)->translatedFormat('l, d M Y - H:i') }} WIB
                                    &middot; oleh {{ $handover->creator->name ?? '-' }}
                                </span>
                            </div>
                        </div>
                        @if($isRead)
                            <span class="bg-emerald-50 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">Selesai</span>
                        @else
                            <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">Baru</span>
                        @endif
                    </div>

                    <p class="text-slate-700 mt-3 mb-3">{{ $handover->message }}</p>

                    @if(!$isRead)
                        <div class="flex justify-end">
                            @if($markReadRoute)
                                <form action="{{ $markReadRoute }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-emerald-50 text-emerald-600 border border-emerald-200 text-sm px-4 py-2 rounded-xl hover:bg-emerald-100 transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                        Tandai Selesai
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-slate-400 italic">Tambahkan route <code>staff.handovers.read</code> supaya tombol ini aktif.</span>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white border border-slate-100 rounded-2xl p-10 text-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Belum ada catatan operan shift.
                </div>
            @endforelse
        </div>
    </div>

    <x-modal name="add-handover-modal" focusable>
        <form method="POST" action="{{ $storeHandoverRoute ?? '#' }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-4">Tulis Catatan Operan</h2>

            @unless($storeHandoverRoute)
                <div class="mb-4 bg-amber-50 text-amber-700 px-4 py-3 rounded-2xl text-sm">
                    Route <code>staff.handovers.store</code> belum terdaftar. Tambahkan route &amp; controller-nya dulu supaya form ini bisa submit.
                </div>
            @endunless

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Pesan untuk Shift Selanjutnya</label>
                    <textarea name="message" rows="4" required placeholder="Contoh: Tolong lanjutkan follow up 5 leads baru dari DM Instagram..."
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm"></textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition active:scale-95 font-medium shadow-sm">
                    Kirim Operan
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
