<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-navy-800 leading-tight">
            {{ __('Panel Kendali Sekretaris') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="bg-gradient-to-r from-navy-700 to-navy-800 rounded-2xl p-8 shadow-lg text-white flex flex-col md:flex-row justify-between items-center mt-4 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-brand-300"></div>
                <div class="absolute -left-5 -bottom-5 w-24 h-24 rounded-full bg-brand-200"></div>
            </div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-1">Selamat datang, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-navy-200">Pantau operasional dan kelola jadwal Pengurus MNCU Future Leader dari panel ini.</p>
            </div>
            <div class="mt-4 md:mt-0 relative z-10">
                <a href="{{ route('admin.schedules.index') }}" class="bg-brand-300 text-navy-900 hover:bg-brand-200 font-semibold px-6 py-3 rounded-xl transition transform active:scale-95 inline-block shadow-md">
                    Kelola Jadwal Minggu Ini
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-brand-300/10 text-brand-400 p-4 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Anggota Piket</p>
                    <p class="text-2xl font-bold text-navy-800">{{ $total_staff }} <span class="text-sm font-normal text-slate-400">Orang</span></p>
                </div>
            </div>

            <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-navy-700/10 text-navy-600 p-4 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Status Minggu Ini</p>
                    @if(isset($current_week) && $current_week)
                        <p class="text-2xl font-bold text-navy-800">
                            {{ $current_week->isPublished() ? 'Published' : 'Draft' }}
                            <span class="text-sm font-normal {{ $current_week->isPublished() ? 'text-emerald-500' : 'text-amber-500' }}">
                                {{ $current_week->isPublished() ? '✓' : '(belum dipublish)' }}
                            </span>
                        </p>
                    @else
                        <p class="text-lg font-bold text-slate-400">Belum dibuat</p>
                    @endif
                </div>
            </div>

            <div class="bg-white border border-slate-200/60 rounded-2xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition">
                <div class="bg-brand-300/10 text-brand-500 p-4 rounded-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-slate-500 text-sm font-medium">Jam Operasional / Shift</p>
                    <p class="text-2xl font-bold text-navy-800">{{ $total_shifts }} <span class="text-sm font-normal text-slate-400">Shift</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200/60 overflow-hidden shadow-sm rounded-2xl">
            <div class="p-6 border-b border-slate-200/60 flex justify-between items-center bg-navy-50/50">
                <h3 class="font-semibold text-navy-800">Jadwal Mendatang</h3>
                <a href="{{ route('admin.schedules.index') }}" class="text-sm text-brand-400 hover:text-brand-500 font-medium">Lihat Semua &rarr;</a>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-slate-100 text-slate-500 font-medium text-sm bg-white">
                                <th class="py-4 px-6">Tanggal</th>
                                <th class="py-4 px-6">Petugas Bertugas</th>
                                <th class="py-4 px-6">Shift / Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcoming_schedules as $schedule)
                                <tr class="border-b border-slate-100 hover:bg-navy-50/30 transition">
                                    <td class="py-4 px-6 font-medium text-navy-800">
                                        {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d M Y') }}
                                        @if(\Carbon\Carbon::parse($schedule->date)->isToday())
                                            <span class="ml-2 bg-brand-300/15 text-brand-400 text-xs px-2 py-0.5 rounded-full font-bold">Hari Ini</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">
                                        {{ $schedule->staff->pluck('name')->join(', ') ?: '—' }}
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 flex items-center gap-2">
                                        {{ $schedule->shift->name }}
                                        <span class="{{ $schedule->shift->location === 'mncu' ? 'bg-navy-700/10 text-navy-700' : 'bg-brand-300/10 text-brand-500' }} px-2 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wide">
                                            {{ $schedule->shift->location }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-500">
                                        Tidak ada jadwal piket dalam waktu dekat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>