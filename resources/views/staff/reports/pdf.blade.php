<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Piket - {{ $report->picketSchedule->shift->name }} - {{ $report->picketSchedule->date }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }
        h2 { text-align: center; color: #0284c7; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; font-size: 14px;}
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        th { background-color: #f8fafc; text-align: left; width: 35%; color: #334155;}
        .image-container { text-align: center; margin-top: 20px; }
        .image-container img { max-width: 100%; max-height: 300px; border: 1px solid #ddd; }
    </style>
</head>
<body>

    <h2>LAPORAN HARIAN PIKET</h2>
    <div class="subtitle">MNCU Future Leader</div>

    <table>
        <tr>
            <th>Tanggal Shift</th>
            <td>{{ \Carbon\Carbon::parse($report->picketSchedule->date)->format('d F Y') }}</td>
        </tr>
        <tr>
            <th>Shift</th>
            <td>{{ $report->picketSchedule->shift->name }} ({{ strtoupper($report->picketSchedule->shift->location) }})</td>
        </tr>
        <tr>
            <th>Petugas Bertugas</th>
            <td>{{ $report->picketSchedule->staff->pluck('name')->join(', ') ?: '-' }}</td>
        </tr>
        <tr>
            <th>Disubmit Oleh</th>
            <td>{{ $report->creator->name }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="2" style="text-align: center; background-color: #e0f2fe; color: #0369a1;">Detail Pekerjaan</th>
        </tr>
        <tr>
            <th>Media Sosial Dibalas</th>
            <td>
                @php $medsos = $report->social_media_status ?? []; @endphp
                {{ isset($medsos['whatsapp']) ? 'WhatsApp, ' : '' }}
                {{ isset($medsos['instagram']) ? 'Instagram, ' : '' }}
                {{ isset($medsos['tiktok']) ? 'TikTok' : '' }}
                @if(empty($medsos)) Tidak ada @endif
            </td>
        </tr>
        <tr>
            <th>Total Leads Follow-Up</th>
            <td>{{ $report->total_leads_followed_up }} orang</td>
        </tr>
        <tr>
            <th>Durasi Live TikTok</th>
            <td>{{ $report->live_tiktok_duration_minutes }} menit</td>
        </tr>
        <tr>
            <th>Catatan Live TikTok</th>
            <td>{{ $report->live_tiktok_notes ?? '-' }}</td>
        </tr>
        <tr>
            <th>Produksi Konten</th>
            <td>
                @if($report->content_title)
                    Judul: {{ $report->content_title }} <br>
                    Platform: {{ $report->content_platform }} <br>
                    Status: {{ $report->content_status }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="2" style="text-align: center; background-color: #e0f2fe; color: #0369a1;">Evaluasi Akhir</th>
        </tr>
        <tr>
            <th>Kendala Lapangan</th>
            <td>{{ $report->issues_encountered }}</td>
        </tr>
        <tr>
            <th>Kesimpulan Shift</th>
            <td>{{ $report->conclusion }}</td>
        </tr>
    </table>

    @if($report->documentation_path)
        <div class="image-container">
            <p><strong>Foto Dokumentasi:</strong></p>
            <!-- dompdf membutuhkan absolute path ke file lokal untuk merender gambar -->
            <img src="{{ storage_path('app/public/' . $report->documentation_path) }}" alt="Dokumentasi">
        </div>
    @endif

</body>
</html>