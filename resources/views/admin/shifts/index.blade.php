<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Manajemen Shift Operasional') }}
            </h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-shift-modal')" 
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Shift
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
                                <th class="py-4 px-4">Nama Shift</th>
                                <th class="py-4 px-4">Jam Kerja</th>
                                <th class="py-4 px-4">Lokasi</th>
                                <th class="py-4 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shifts as $index => $shift)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-4">{{ $index + 1 }}</td>
                                    <td class="py-4 px-4 font-medium text-slate-800">{{ $shift->name }}</td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }} WIB
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($shift->location === 'mncu')
                                            <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-sm font-medium uppercase tracking-wide">MNCU</span>
                                        @else
                                            <span class="bg-purple-50 text-purple-700 px-3 py-1 rounded-full text-sm font-medium uppercase tracking-wide">MKS</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-shift-modal-{{ $shift->id }}')"
                                                class="text-sky-600 bg-sky-50 hover:bg-sky-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95 mr-2">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus shift ini?')" class="text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <x-modal name="edit-shift-modal-{{ $shift->id }}" focusable>
                                    <form method="POST" action="{{ route('admin.shifts.update', $shift->id) }}" class="p-6">
                                        @csrf
                                        @method('PUT')
                                        <h2 class="text-lg font-medium text-slate-900 mb-4">Edit Shift Operasional</h2>

                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Nama Shift</label>
                                                <input type="text" name="name" value="{{ $shift->name }}" required
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700">Jam Mulai</label>
                                                    <input type="time" name="start_time" value="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}" required
                                                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700">Jam Selesai</label>
                                                    <input type="time" name="end_time" value="{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}" required
                                                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Lokasi Tugas</label>
                                                <select name="location" required
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                    <option value="mncu" {{ $shift->location === 'mncu' ? 'selected' : '' }}>MNCU</option>
                                                    <option value="mks" {{ $shift->location === 'mks' ? 'selected' : '' }}>MKS</option>
                                                </select>
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
                                        Belum ada data shift operasional.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-shift-modal" focusable>
        <form method="POST" action="{{ route('admin.shifts.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-4">Tambah Shift Operasional</h2>

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Shift</label>
                    <input type="text" id="name" name="name" required placeholder="Contoh: Pagi / Full Time"
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-slate-700">Jam Mulai</label>
                        <input type="time" id="start_time" name="start_time" required
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-slate-700">Jam Selesai</label>
                        <input type="time" id="end_time" name="end_time" required
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                    </div>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700">Lokasi Tugas</label>
                    <select id="location" name="location" required
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                        <option value="mncu">MNCU</option>
                        <option value="mks">MKS</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click.$dispatch('close') 
                        class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-2xl hover:bg-slate-200 transition active:scale-95 font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-sky-500 text-white rounded-2xl hover:bg-sky-600 transition active:scale-95 font-medium shadow-sm">
                    Simpan Shift
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>