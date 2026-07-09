<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Formulir Laporan Akhir Shift') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white border border-slate-100 shadow-sm rounded-2xl overflow-hidden mb-8">
            
            <div class="bg-sky-50 p-6 border-b border-sky-100">
                <h3 class="font-semibold text-sky-800">Informasi Jadwal</h3>
                <p class="text-sky-700 text-sm mt-1">Anda melaporkan untuk shift: <strong>{{ $schedule->shift->name }}</strong> bersama <strong>{{ $schedule->staff->pluck('name')->join(', ') ?: 'tidak ada rekan lain' }}</strong> pada <strong>{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}</strong>.</p>
            </div>

            <!-- Pastikan enctype="multipart/form-data" untuk upload foto -->
            <form action="{{ route('staff.reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="picket_schedule_id" value="{{ $schedule->id }}">

                <!-- Media Sosial & Leads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Media Sosial Terbalas</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="social_media_status[whatsapp]" value="true" class="rounded text-sky-500 focus:ring-sky-500">
                                <span class="text-slate-700">WhatsApp</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="social_media_status[instagram]" value="true" class="rounded text-sky-500 focus:ring-sky-500">
                                <span class="text-slate-700">Instagram (DM/Komentar)</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="social_media_status[tiktok]" value="true" class="rounded text-sky-500 focus:ring-sky-500">
                                <span class="text-slate-700">TikTok (Komentar)</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Total Leads yang di-Follow Up</label>
                        <input type="number" name="total_leads_followed_up" min="0" value="0" required
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 focus:ring focus:ring-sky-200 transition shadow-sm">
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Produksi Konten -->
                <h4 class="font-medium text-slate-800">Produksi Konten (Opsional)</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Judul Konten</label>
                        <input type="text" name="content_title" placeholder="Contoh: Info Beasiswa"
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Platform</label>
                        <input type="text" name="content_platform" placeholder="Instagram Reels"
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="content_status"
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm">
                            <option value="">-- Pilih Status --</option>
                            <option value="Draft">Draft / Editing</option>
                            <option value="Published">Telah Dipublish</option>
                        </select>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Live TikTok -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Durasi Live TikTok (Menit)</label>
                        <input type="number" name="live_tiktok_duration_minutes" min="0" value="0" required
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Catatan Live TikTok</label>
                        <textarea name="live_tiktok_notes" rows="2" placeholder="Insight atau kendala saat live..."
                            class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm"></textarea>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Kesimpulan, Kendala, & Foto -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kendala di Lapangan <span class="text-rose-500">*</span></label>
                    <textarea name="issues_encountered" rows="3" required placeholder="Tuliskan kendala operasional yang terjadi..."
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Kesimpulan Shift <span class="text-rose-500">*</span></label>
                    <textarea name="conclusion" rows="3" required placeholder="Kesimpulan umum shift hari ini..."
                        class="mt-1 block w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-2xl focus:border-sky-400 transition shadow-sm"></textarea>
                </div>

                <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Upload Foto Dokumentasi (Opsional)</label>
                    <input type="file" name="documentation" accept="image/*"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition">
                </div>

                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-8 py-3 rounded-2xl shadow-sm transition transform active:scale-95 text-lg">
                        Submit Laporan Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>