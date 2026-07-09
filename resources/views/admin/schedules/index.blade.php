{{--
    REDESIGN TOTAL sesuai permintaan: jadwal dibuat langsung untuk 1 minggu penuh
    (Senin-Minggu), per-minggu punya status Draft/Published, minggu lama tetap
    tersimpan sebagai riwayat, dan pengisian petugas per slot langsung pilih
    nama staff (search/checklist) — bukan pilih Team lagi.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-3">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Jadwal Piket Mingguan') }}
            </h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'generate-week-modal')"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Generate Minggu Baru
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 bg-rose-50 text-rose-700 px-4 py-3 rounded-2xl">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Bar kontrol: pindah minggu (riwayat) + publish --}}
        <div class="bg-white border border-slate-100 rounded-2xl p-5 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                @if($allWeeks->isNotEmpty())
                    <form method="GET" action="{{ route('admin.schedules.index') }}">
                        <label class="text-xs text-slate-400 block mb-1">Pilih Minggu</label>
                        <select name="week" onchange="this.form.submit()"
                                class="bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl text-sm focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                            @foreach($allWeeks as $w)
                                <option value="{{ $w->id }}" {{ $scheduleWeek && $scheduleWeek->id === $w->id ? 'selected' : '' }}>
                                    {{ $w->week_start_date->translatedFormat('d M') }} – {{ $w->week_end_date->translatedFormat('d M Y') }}
                                    ({{ $w->isPublished() ? 'Published' : 'Draft' }})
                                </option>
                            @endforeach
                        </select>
                    </form>
                @else
                    <p class="text-slate-500 text-sm">Belum ada jadwal yang pernah dibuat.</p>
                @endif
            </div>

            @if($scheduleWeek && $scheduleWeek->isDraft())
                <form method="POST" action="{{ route('admin.schedules.publishWeek', $scheduleWeek->id) }}">
                    @csrf
                    <button type="submit" onclick="return confirm('Publish jadwal minggu ini? Staff akan langsung bisa melihatnya.')"
                            class="bg-emerald-500 hover:bg-emerald-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        Publish Minggu Ini
                    </button>
                </form>
            @endif
        </div>

        @if($scheduleWeek)
            <div class="mb-4 flex items-center gap-3">
                <h3 class="text-lg font-semibold text-slate-800">
                    Minggu {{ $scheduleWeek->week_start_date->translatedFormat('d M') }} – {{ $scheduleWeek->week_end_date->translatedFormat('d M Y') }}
                </h3>
                @if($scheduleWeek->isDraft())
                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-3 py-1 rounded-full">Draft — belum terlihat staff</span>
                @else
                    <span class="bg-emerald-100 text-emerald-700 text-xs font-semibold px-3 py-1 rounded-full">Published</span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-7 gap-4">
                @php $cursor = $scheduleWeek->week_start_date->copy(); @endphp
                @for($i = 0; $i < 7; $i++)
                    @php
                        $dateKey = $cursor->toDateString();
                        $items = $scheduleByDate->get($dateKey, collect());
                    @endphp
                    <div class="bg-white border rounded-2xl p-3 {{ $cursor->isToday() ? 'border-sky-400 ring-2 ring-sky-100' : 'border-slate-100' }}">
                        <div class="text-center mb-3 pb-2 border-b border-slate-100">
                            <p class="text-xs text-slate-400 uppercase font-semibold">{{ $cursor->translatedFormat('D') }}</p>
                            <p class="text-lg font-bold text-slate-800">{{ $cursor->format('d') }}</p>
                        </div>

                        <div class="space-y-3">
                            @forelse($items as $item)
                                <div class="rounded-xl border p-3 {{ $item->shift->location === 'mncu' ? 'bg-sky-50 border-sky-100' : 'bg-purple-50 border-purple-100' }}">
                                    <p class="text-[11px] font-semibold {{ $item->shift->location === 'mncu' ? 'text-sky-700' : 'text-purple-700' }}">
                                        {{ \Carbon\Carbon::parse($item->shift->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($item->shift->end_time)->format('H:i') }}
                                    </p>
                                    <p class="text-sm font-semibold text-slate-800 mb-2">{{ $item->shift->name }}</p>

                                    <div class="flex items-center flex-wrap gap-1 mb-2 min-h-[24px]">
                                        @forelse($item->staff as $person)
                                            <span title="{{ $person->name }}" class="w-6 h-6 rounded-full bg-white shadow-sm border border-slate-200 text-[10px] font-semibold flex items-center justify-center text-slate-600">
                                                {{ strtoupper(substr($person->name, 0, 1)) }}
                                            </span>
                                        @empty
                                            <span class="text-[11px] text-slate-400 italic">Belum ada petugas</span>
                                        @endforelse
                                    </div>

                                    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'assign-staff-modal-{{ $item->id }}')"
                                            class="text-xs font-medium text-sky-600 hover:text-sky-700 hover:underline">
                                        Kelola Petugas
                                    </button>
                                </div>

                                <x-modal name="assign-staff-modal-{{ $item->id }}" focusable>
                                    <div class="p-6" x-data="{ search: '', selected: {{ $item->staff->pluck('id')->values()->toJson() }} }">
                                        <h2 class="text-lg font-medium text-slate-900 mb-1">Kelola Petugas</h2>
                                        <p class="text-sm text-slate-500 mb-4">
                                            {{ $item->shift->name }} &middot; {{ $cursor->copy()->translatedFormat('l, d M Y') }}
                                        </p>

                                        <form method="POST" action="{{ route('admin.schedules.assign', $item->id) }}">
                                            @csrf
                                            <input type="text" x-model="search" placeholder="Cari nama staff..."
                                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl mb-3 text-sm focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">

                                            <div class="max-h-56 overflow-y-auto space-y-1 border border-slate-100 rounded-2xl p-2">
                                                @forelse($staffList as $staffMember)
                                                    <label x-show="{{ \Illuminate\Support\Js::from(strtolower($staffMember->name)) }}.includes(search.toLowerCase())"
                                                           class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 cursor-pointer">
                                                        <input type="checkbox" value="{{ $staffMember->id }}" name="user_ids[]"
                                                               x-model.number="selected"
                                                               class="rounded border-slate-300 text-sky-500 focus:ring-sky-400">
                                                        <span class="text-sm text-slate-700">{{ $staffMember->name }}</span>
                                                    </label>
                                                @empty
                                                    <p class="text-sm text-slate-400 text-center py-4">Belum ada akun staff. Tambahkan lewat halaman Manajemen Pengguna.</p>
                                                @endforelse
                                            </div>

                                            <div class="mt-6 flex justify-between items-center gap-3">
                                                <button type="button" x-on:click="$dispatch('close')"
                                                        class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium text-sm">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                        class="px-5 py-2.5 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition active:scale-95 font-medium shadow-sm text-sm">
                                                    Simpan Petugas
                                                </button>
                                            </div>
                                        </form>

                                        <form method="POST" action="{{ route('admin.schedules.destroy', $item->id) }}" class="mt-3 text-center" onsubmit="return confirm('Hapus slot shift ini untuk tanggal tersebut?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-600 hover:underline">
                                                Hapus slot ini
                                            </button>
                                        </form>
                                    </div>
                                </x-modal>
                            @empty
                                <p class="text-xs text-slate-300 text-center italic py-4">Tidak ada shift</p>
                            @endforelse
                        </div>
                    </div>
                    @php $cursor->addDay(); @endphp
                @endfor
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-2xl p-12 text-center text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Belum ada jadwal yang dibuat. Klik <strong>"Generate Minggu Baru"</strong> untuk membuat draft jadwal minggu ini.
            </div>
        @endif
    </div>

    <x-modal name="generate-week-modal" focusable>
        <form method="POST" action="{{ route('admin.schedules.generateWeek') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-2">Generate Draft Jadwal Mingguan</h2>
            <p class="text-sm text-slate-500 mb-4">Pilih tanggal berapa saja dalam minggu yang dituju — sistem otomatis mengambil Senin s.d Minggu dari tanggal itu, lalu membuat 1 slot kosong untuk tiap shift di tiap hari. Minggu-minggu sebelumnya tidak akan hilang atau tertimpa.</p>

            <div>
                <label class="block text-sm font-medium text-slate-700">Tanggal (dalam minggu yang dituju)</label>
                <input type="date" name="week_start_date" required value="{{ now()->toDateString() }}"
                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition active:scale-95 font-medium shadow-sm">
                    Generate Draft
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
