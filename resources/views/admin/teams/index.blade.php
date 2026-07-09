<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Manajemen Kelompok (Teams)') }}
            </h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-team-modal')" 
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Kelompok
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if (session('success'))
            <div class="mb-4 bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-slate-100 overflow-hidden shadow-sm rounded-2xl">
            <div class="p-6 text-slate-900">
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-slate-100 text-slate-500 font-medium">
                                <th class="py-4 px-4">No</th>
                                <th class="py-4 px-4">Nama Kelompok</th>
                                <th class="py-4 px-4">Total Anggota</th>
                                <th class="py-4 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teams as $index => $team)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-4">{{ $index + 1 }}</td>
                                    <td class="py-4 px-4 font-medium text-slate-800">{{ $team->name }}</td>
                                    <td class="py-4 px-4">
                                        <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-sm">
                                            {{ $team->users()->count() }} Anggota
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-team-modal-{{ $team->id }}')"
                                                class="text-sky-600 bg-sky-50 hover:bg-sky-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95 mr-2">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus kelompok ini?')" class="text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <x-modal name="edit-team-modal-{{ $team->id }}" focusable>
                                    <form method="POST" action="{{ route('admin.teams.update', $team->id) }}" class="p-6">
                                        @csrf
                                        @method('PUT')
                                        <h2 class="text-lg font-medium text-slate-900 mb-4">Edit Nama Kelompok</h2>

                                        <div>
                                            <label for="name-{{ $team->id }}" class="block text-sm font-medium text-slate-700">Nama Kelompok</label>
                                            <input type="text" id="name-{{ $team->id }}" name="name" value="{{ $team->name }}" required
                                                class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
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
                                    <td colspan="4" class="py-8 text-center text-slate-500">
                                        Belum ada data kelompok.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <x-modal name="add-team-modal" focusable>
        <form method="POST" action="{{ route('admin.teams.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-4">
                Tambah Kelompok Piket
            </h2>

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Kelompok</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Senin Shift 1"
                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" 
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition active:scale-95 font-medium shadow-sm">
                    Simpan Kelompok
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>