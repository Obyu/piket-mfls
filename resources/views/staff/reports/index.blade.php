<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Riwayat Laporan Shift') }}
            </h2>
            <a href="{{ route('staff.reports.create') }}" 
               class="bg-sky-500 hover:bg-sky-600 text-white font-medium px-5 py-2.5 rounded-2xl shadow-sm transition transform active:scale-95 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Buat Laporan Hari Ini
            </a>
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
            <div class="p-6 text-slate-900">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-slate-100 text-slate-500 font-medium">
                                <th class="py-4 px-4">Tanggal Shift</th>
                                <th class="py-4 px-4">Kelompok</th>
                                <th class="py-4 px-4">Dilaporkan Oleh</th>
                                <th class="py-4 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-4 font-medium text-slate-800">
                                        {{ \Carbon\Carbon::parse($report->picketSchedule->date)->translatedFormat('l, d M Y') }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ $report->picketSchedule->shift->name ?? '-' }}
                                        <span class="text-slate-400 text-xs block">{{ $report->picketSchedule->staff->pluck('name')->join(', ') ?: 'Tidak ada data petugas' }}</span>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600">
                                        {{ $report->creator->name }}
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a href="{{ route('staff.reports.export', $report->id) }}" 
                                           class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 hover:bg-emerald-100 px-4 py-2 rounded-2xl font-medium transition active:scale-95 text-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                            Download PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-slate-500">
                                        Belum ada laporan yang disubmit.
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