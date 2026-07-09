{{--
    Halaman BARU sesuai requirement bagian B "Manajemen Pengguna (Users)".
    Mengikuti pola yang sama persis dengan admin/teams & admin/shifts:
    tabel + modal tambah + modal edit, tanpa halaman create/edit terpisah.
--}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Manajemen Pengguna') }}
            </h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-user-modal')"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Akun
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

        <div class="bg-white border border-slate-100 overflow-hidden shadow-sm rounded-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-slate-100 text-slate-500 font-medium text-sm">
                            <th class="py-4 px-6">Nama</th>
                            <th class="py-4 px-6">Email</th>
                            <th class="py-4 px-6">Role</th>
                            <th class="py-4 px-6">Kelompok</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
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
                                    @if($user->role === 'admin')
                                        <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold uppercase">Admin</span>
                                    @else
                                        <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-xs font-semibold uppercase">Staff</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-600">{{ $user->team->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-right">
                                    <button type="button"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-user-modal-{{ $user->id }}')"
                                            class="text-sky-600 bg-sky-50 hover:bg-sky-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95 mr-2">
                                        Edit
                                    </button>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus akun {{ $user->name }}?')" class="text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <x-modal name="edit-user-modal-{{ $user->id }}" focusable>
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="p-6">
                                    @csrf
                                    @method('PUT')
                                    <h2 class="text-lg font-medium text-slate-900 mb-4">Edit Akun: {{ $user->name }}</h2>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                            <input type="text" name="name" value="{{ $user->name }}" required
                                                class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Email</label>
                                            <input type="email" name="email" value="{{ $user->email }}" required
                                                class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700">Password Baru (kosongkan jika tidak diubah)</label>
                                            <input type="password" name="password" placeholder="••••••••"
                                                class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Role</label>
                                                <select name="role" required
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                    <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Kelompok</label>
                                                <select name="team_id"
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                    <option value="">-- Tanpa Kelompok --</option>
                                                    @foreach($teams as $team)
                                                        <option value="{{ $team->id }}" {{ $user->team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
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
                                            Simpan Perubahan
                                        </button>
                                    </div>
                                </form>
                            </x-modal>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">
                                    Belum ada data pengguna.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal name="add-user-modal" focusable>
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-4">Tambah Akun Baru</h2>

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
                <div>
                    <label class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" name="password" required minlength="8"
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
                            @foreach($teams as $team)
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
