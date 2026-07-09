{{--
    Halaman BARU — belum ada sebelumnya di project ini.
    Diadaptasi dari desain Stitch "pengaturan_shiftmanager".

    Variabel yang diharapkan controller (silakan sesuaikan nama kolom dengan skema aslimu):
    - $users : koleksi User (id, name, email, role, team (relasi, nullable), is_active)

    Route yang dipakai / diasumsikan (didaftarkan sendiri di web.php):
    - admin.settings.index        GET   -> halaman ini
    - admin.settings.users.store  POST  -> simpan akun baru (opsional, dibungkus Route::has)
    - admin.settings.users.toggle PATCH -> aktif/nonaktifkan akun (opsional, dibungkus Route::has)

    Kalau route di atas belum ada, form & tombol tetap tampil tapi tidak akan submit kemana-mana
    (supaya file ini aman di-drop ke project tanpa langsung error).
--}}
@php
    $storeUserRoute = Route::has('admin.settings.users.store') ? route('admin.settings.users.store') : null;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ tab: 'akun' }">

        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <p class="text-slate-500 mb-6">Kelola akun pengguna serta akses cepat ke pengaturan kelompok &amp; shift operasional.</p>

        <!-- Tabs -->
        <div class="mb-6 border-b border-slate-200">
            <nav class="flex gap-6">
                <button @click="tab = 'akun'"
                        :class="tab === 'akun' ? 'border-sky-500 text-sky-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 text-sm transition">
                    Manajemen Akun
                </button>
                <button @click="tab = 'lainnya'"
                        :class="tab === 'lainnya' ? 'border-sky-500 text-sky-600 font-semibold' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap py-3 px-1 border-b-2 text-sm transition">
                    Kelompok &amp; Shift
                </button>
            </nav>
        </div>

        <!-- Tab: Manajemen Akun -->
        <div x-show="tab === 'akun'" x-transition.opacity>
            <div class="bg-white border border-slate-100 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-semibold text-slate-800">Daftar Pengguna</h3>
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-user-modal')"
                            class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Tambah Akun
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-slate-100 text-slate-500 font-medium text-sm bg-white">
                                <th class="py-4 px-6">Nama Pengguna</th>
                                <th class="py-4 px-6">Email</th>
                                <th class="py-4 px-6">Role</th>
                                <th class="py-4 px-6">Kelompok</th>
                                <th class="py-4 px-6">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users ?? [] as $user)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition" x-data="{ isActive: {{ ($user->is_active ?? true) ? 'true' : 'false' }} }">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-sky-50 text-sky-700 flex items-center justify-center font-semibold text-sm">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-slate-800">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $user->email }}</td>
                                    <td class="py-4 px-6">
                                        @if(($user->role ?? '') === 'admin')
                                            <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold uppercase">Admin</span>
                                        @else
                                            <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-xs font-semibold uppercase">Staff</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-slate-600">{{ $user->team->name ?? '-' }}</td>
                                    <td class="py-4 px-6">
                                        {{-- Toggle visual (Alpine, client-side). Hubungkan ke route admin.settings.users.toggle kalau sudah dibuat. --}}
                                        <button type="button" @click="isActive = !isActive"
                                                :class="isActive ? 'bg-sky-500' : 'bg-slate-200'"
                                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-300">
                                            <span :class="isActive ? 'translate-x-6' : 'translate-x-1'"
                                                  class="inline-block h-4 w-4 transform rounded-full bg-white transition duration-300"></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500">
                                        Belum ada data pengguna untuk ditampilkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab: Kelompok & Shift (quick links, tidak duplikat CRUD yang sudah ada) -->
        <div x-show="tab === 'lainnya'" x-transition.opacity style="display: none;">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('admin.teams.index') }}" class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex items-center gap-4">
                    <div class="bg-sky-50 text-sky-600 p-4 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Kelompok Piket</h3>
                        <p class="text-sm text-slate-500">Kelola kelompok &amp; anggotanya di halaman Kelompok.</p>
                    </div>
                </a>

                <a href="{{ route('admin.shifts.index') }}" class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition flex items-center gap-4">
                    <div class="bg-purple-50 text-purple-600 p-4 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0" /></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-800">Shift Operasional</h3>
                        <p class="text-sm text-slate-500">Atur jam kerja &amp; lokasi shift di halaman Shift.</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <x-modal name="add-user-modal" focusable>
        <form method="POST" action="{{ $storeUserRoute ?? '#' }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-4">Tambah Akun Baru</h2>

            @unless($storeUserRoute)
                <div class="mb-4 bg-amber-50 text-amber-700 px-4 py-3 rounded-2xl text-sm">
                    Route <code>admin.settings.users.store</code> belum terdaftar. Tambahkan route &amp; controller-nya dulu supaya form ini bisa submit.
                </div>
            @endunless

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" required
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Role</label>
                        <select name="role" required
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kelompok</label>
                        <select name="team_id"
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                            <option value="">-- Tanpa Kelompok --</option>
                            @foreach($teams ?? [] as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition active:scale-95 font-medium shadow-sm">
                    Simpan Akun
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
