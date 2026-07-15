{{--
    Sidebar navigasi dengan desain navy + gold mengikuti identitas beasiswamncu.com.
    Hanya tampil di layar md ke atas; untuk mobile, menu tetap memakai dropdown
    di layouts/navigation.blade.php.
--}}
@php
    $isAdminArea = request()->routeIs('admin.*');
@endphp

<aside class="hidden md:flex md:flex-col md:fixed md:inset-y-0 md:left-0 md:w-[272px] bg-gradient-to-b from-navy-700 to-navy-800 px-4 py-6 z-40 shadow-xl">

    <a href="{{ $isAdminArea ? route('admin.dashboard') : route('staff.dashboard') }}" class="flex items-center gap-3 px-3 mb-8">
        <img src="{{ asset('logo.svg') }}" alt="Logo" class="h-10 w-10 shrink-0">
        <div class="min-w-0">
            <p class="font-bold text-white leading-tight truncate">Aplikasi Piket</p>
            <p class="text-xs text-brand-200 truncate">MNCU Future Leader</p>
        </div>
    </a>

    <nav class="flex-1 space-y-1 overflow-y-auto sidebar-scroll">
        @if($isAdminArea)
            {{-- ==== MENU SEKRETARIS / ADMIN ==== --}}
            <p class="px-4 text-xs font-semibold text-navy-400 uppercase tracking-wider mb-2">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.schedules.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('admin.schedules.*') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Jadwal Piket
            </a>

            <a href="{{ route('admin.shifts.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('admin.shifts.*') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" /></svg>
                Shift Operasional
            </a>

            @if(Route::has('admin.users.index'))
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('admin.users.*') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Pengguna
                </a>
            @endif
        @else
            {{-- ==== MENU STAFF ==== --}}
            <p class="px-4 text-xs font-semibold text-navy-400 uppercase tracking-wider mb-2">Menu Staff</p>

            <a href="{{ route('staff.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('staff.dashboard') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Dashboard
            </a>

            <a href="{{ route('staff.leads.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('staff.leads.*') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Leads / Prospek
            </a>

            <a href="{{ route('staff.reports.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('staff.reports.*') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4M7 21h10a2 2 0 002-2V7.414a1 1 0 00-.293-.707l-4.414-4.414A1 1 0 0013.586 2H7a2 2 0 00-2 2v15a2 2 0 002 2z" /></svg>
                Laporan Shift
            </a>

            @if(Route::has('staff.notifications.index'))
                <a href="{{ route('staff.notifications.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium text-sm {{ request()->routeIs('staff.notifications.*') ? 'bg-brand-300/15 text-brand-200 border-l-[3px] border-brand-300' : 'text-navy-200 hover:bg-white/5 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    Notifikasi Operan
                </a>
            @endif
        @endif
    </nav>

    <div class="mt-auto pt-4 border-t border-white/10">
        <div class="flex items-center gap-3 px-3 py-2">
            <div class="h-9 w-9 rounded-full bg-brand-300/20 text-brand-200 flex items-center justify-center font-semibold text-sm shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-navy-300 truncate">{{ $isAdminArea ? 'Sekretaris' : 'Staff Piket' }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Log Out" class="p-2 text-navy-400 hover:text-red-400 hover:bg-white/5 rounded-xl transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                </button>
            </form>
        </div>
    </div>
</aside>
