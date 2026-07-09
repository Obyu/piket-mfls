<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Database Pendaftar Beasiswa') }}
            </h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-lead-modal')" 
                    class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Prospek
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
                                <th class="py-4 px-4">Nama</th>
                                <th class="py-4 px-4">Kontak (WhatsApp)</th>
                                <th class="py-4 px-4">Minat Jurusan</th>
                                <th class="py-4 px-4">Status</th>
                                <th class="py-4 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leads as $lead)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-4 font-medium text-slate-800">
                                        {{ $lead->name }}
                                        <div class="text-xs text-slate-400 font-normal">{{ $lead->email ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->whatsapp_number) }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            {{ $lead->whatsapp_number }}
                                        </a>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">{{ $lead->major_interest ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        @if($lead->status === 'new')
                                            <span class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-semibold">Baru</span>
                                        @elseif($lead->status === 'followed_up')
                                            <span class="bg-amber-50 text-amber-700 px-3 py-1 rounded-full text-xs font-semibold">Di-follow Up</span>
                                        @elseif($lead->status === 'interested')
                                            <span class="bg-sky-50 text-sky-700 px-3 py-1 rounded-full text-xs font-semibold">Tertarik</span>
                                        @elseif($lead->status === 'not_interested')
                                            <span class="bg-rose-50 text-rose-700 px-3 py-1 rounded-full text-xs font-semibold">Tidak Tertarik</span>
                                        @elseif($lead->status === 'registered')
                                            <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold">Telah Daftar</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-lead-modal-{{ $lead->id }}')"
                                                class="text-sky-600 bg-sky-50 hover:bg-sky-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95 text-sm mr-2">
                                            Update Status
                                        </button>

                                        <form action="{{ route('staff.leads.destroy', $lead->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus data prospek ini?')" class="text-rose-600 bg-rose-50 hover:bg-rose-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95 text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                                <x-modal name="edit-lead-modal-{{ $lead->id }}" focusable>
                                    <form method="POST" action="{{ route('staff.leads.update', $lead->id) }}" class="p-6">
                                        @csrf
                                        @method('PUT')
                                        <h2 class="text-lg font-medium text-slate-900 mb-4">Update Data &amp; Status: {{ $lead->name }}</h2>

                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                                                <input type="text" name="name" value="{{ $lead->name }}" required
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                            </div>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700">No. WhatsApp</label>
                                                    <input type="text" name="whatsapp_number" value="{{ $lead->whatsapp_number }}" required
                                                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700">Minat Jurusan</label>
                                                    <input type="text" name="major_interest" value="{{ $lead->major_interest }}"
                                                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Status Prospek</label>
                                                <select name="status" required
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                                                    <option value="new" {{ $lead->status === 'new' ? 'selected' : '' }}>Baru</option>
                                                    <option value="followed_up" {{ $lead->status === 'followed_up' ? 'selected' : '' }}>Sudah di-Follow Up</option>
                                                    <option value="interested" {{ $lead->status === 'interested' ? 'selected' : '' }}>Tertarik</option>
                                                    <option value="not_interested" {{ $lead->status === 'not_interested' ? 'selected' : '' }}>Tidak Tertarik</option>
                                                    <option value="registered" {{ $lead->status === 'registered' ? 'selected' : '' }}>Telah Mendaftar (Registered)</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-medium text-slate-700">Catatan Khusus (Opsional)</label>
                                                <textarea name="notes" rows="2"
                                                    class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">{{ $lead->notes }}</textarea>
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
                                        Belum ada data calon pendaftar / leads.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-lead-modal" focusable>
        <form method="POST" action="{{ route('staff.leads.store') }}" class="p-6">
            @csrf
            <h2 class="text-lg font-medium text-slate-900 mb-4">Tambah Prospek Baru</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">No. WhatsApp</label>
                        <input type="text" name="whatsapp_number" required placeholder="08..."
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Minat Jurusan</label>
                        <input type="text" name="major_interest" placeholder="Bisnis, Komunikasi, dll..."
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Status Prospek</label>
                    <select name="status" required
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 focus:ring-opacity-50 transition shadow-sm">
                        <option value="new">Baru</option>
                        <option value="followed_up">Sudah di-Follow Up</option>
                        <option value="interested">Tertarik</option>
                        <option value="not_interested">Tidak Tertarik</option>
                        <option value="registered">Telah Mendaftar (Registered)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Catatan Khusus (Opsional)</label>
                    <textarea name="notes" rows="2"
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
                    Simpan Prospek
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>