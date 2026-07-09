{{--
    Sidebar navigasi gaya "ShiftManager" (mengikuti desain Stitch "Luminous Operations").
    Hanya tampil di layar md ke atas; untuk mobile, menu tetap memakai dropdown
    di layouts/navigation.blade.php supaya tidak mengubah pengalaman mobile yang sudah ada.

    Menu berbeda tergantung apakah request saat ini ada di grup route "admin.*" atau "staff.*".
    Link ke halaman BARU (Pengguna & Notifikasi Operan) dibungkus Route::has() supaya sidebar ini
    tidak error walau route-nya belum kamu daftarkan di web.php.
--}}
@php
    $isAdminArea = request()->routeIs('admin.*');
@endphp

<aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 md:left-0 md:w-[280px] bg-white border-r border-slate-100 px-4 py-6 z-40">

    <a href="{{ $isAdminArea ? route('admin.dashboard') : route('staff.dashboard') }}" class="flex items-center gap-3 px-2 mb-8">
        <x-application-logo class="h-9 w-9 fill-current text-sky-500 shrink-0" />
        <div class="min-w-0">
            <p class="font-bold text-slate-800 leading-tight truncate">Aplikasi Piket</p>
            <p class="text-xs text-slate-400 truncate">MNCU Future Leader</p>
        </div>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto">
        @if($isAdminArea)
            {{-- ==== MENU SEKRETARIS / ADMIN ==== --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.schedules.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('admin.schedules.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Jadwal Piket
            </a>

            <a href="{{ route('admin.shifts.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('admin.shifts.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" /></svg>
                Shift Operasional
            </a>

            <a href="{{ route('admin.teams.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('admin.teams.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Kelompok
            </a>

            @if(Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('admin.users.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Pengguna
                </a>
            @endif
        @else
            {{-- ==== MENU STAFF ==== --}}
            <a href="{{ route('staff.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('staff.dashboard') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>

            <a href="{{ route('staff.leads.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('staff.leads.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Leads / Prospek
            </a>

            <a href="{{ route('staff.reports.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('staff.reports.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-4.414-4.414A1 1 0 0013.586 2H7a2 2 0 00-2 2v15a2 2 0 002 2z" /></svg>
                Laporan Shift
            </a>

            @if(Route::has('staff.notifications.index'))
                <a href="{{ route('staff.notifications.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition font-medium text-sm {{ request()->routeIs('staff.notifications.*') ? 'bg-sky-50 text-sky-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    Notifikasi Operan
                </a>
            @endif
        @endif
    </nav>

    <div class="mt-auto pt-4 border-t border-slate-100">
        <div class="flex items-center gap-3 px-2 py-2">
            <div class="h-9 w-9 rounded-full bg-sky-100 text-sky-700 flex items-center justify-center font-semibold text-sm shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ $isAdminArea ? 'Sekretaris' : 'Staff Piket' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Log Out" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
