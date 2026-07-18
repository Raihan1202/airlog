<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logbook - {{ $logbook->judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }

        @media print {
            .print-button, .close-button, .navbar-header, .no-print {
                display: none !important;
            }
            body {
                margin: 0 !important;
                font-size: 12px;
            }
            table {
                width: 100%;
            }
            td, th {
                page-break-inside: avoid;
            }
            @page {
                size: auto;
                margin: 10mm;
            }
        }

        .navbar {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }

        .navbar-header {
            padding: 0 20px;
        }

        .close-button, .print-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
        }

        .close-button {
            background: #dc3545;
        }

        .close-button:hover {
            background: #c82333;
        }

        .print-button:hover {
            background: #0056b3;
        }

        header {
            background-color: #fff;
            padding: 10px 0;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo {
            width: 70px;
            height: 70px;
            background-color: #ccc;
            border-radius: 50%;
            margin-left: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .logo img {
            width: 70px;
            height: 70px;
            object-fit: cover;
        }

        .title-container {
            flex-grow: 1;
            text-align: center;
        }

        .title-container h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .title-container h4 {
            margin: 5px 0 0 0;
            font-size: 16px;
            font-weight: normal;
        }

        .info-section {
            margin: 0 auto;
            width: 95%;
        }

        .section-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        .left-column {
            flex: 7;
        }

        .right-column {
            flex: 3;
        }

        .info-table {
            border-collapse: collapse;
            width: 100%;
            max-width: 100%;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .info-table td {
            padding: 8px;
            border: none;
            vertical-align: top;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .info-left {
            text-align: left;
            width: 20%;
            font-weight: bold;
        }

        .info-center {
            text-align: left;
            width: 40%;
        }

        .info-right {
            text-align: right;
            width: 40%;
            padding: 8px;
        }

        .teknisi-container {
            display: inline-block;
            text-align: left;
            margin-right: 50px;
            vertical-align: bottom;
        }

        .underline {
            display: inline-block;
            border-bottom: 1px solid black;
            width: 150px;
            vertical-align: bottom;
            padding-bottom: 2px;
        }

        .logbook-table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .logbook-table th,
        .logbook-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .logbook-table th {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
        }

        .logbook-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .signature-section {
            width: 95%;
            margin: 20px;
            text-align: right;
        }

        .signature-container {
            display: inline-block;
            margin-top: 50px;
            text-align: center;
        }

        .signature-container img {
            width: 200px;
            height: auto;
            margin-top: 10px;
        }

        .shift-checkbox {
            margin: 0 5px;
        }

        .no-col {
            width: 40px;
            text-align: center;
        }

        .time-col {
            width: 120px;
            text-align: center;
        }

        .duration-col {
            width: 80px;
            text-align: center;
        }
		
		.signature-col {
            width: 95px;
            text-align: center !important; 
        }
        
        .signature-col img {
            /* Membuat gambar Paraf di kolom tabel berada di tengah */
            display: block;
            margin: 0 auto; 
            width: 50px;
            height: 25px;
        }

        .shift-checkbox {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar no-print">
        <div class="navbar-header">
            <button class="close-button" onclick="window.history.back()">← Kembali</button>
            Logbook - {{ $logbook->judul }}
            <button class="print-button" onclick="window.print()">🖨️ Print Logbook</button>
        </div>
    </nav>

    <div id="view_pdf">
        <header>
            <div class="logo">
                <img src="/assets/img/branding/logo-small.png" alt="Logo" style="width: 70px; height: 70px;" onerror="this.style.display='none'">
            </div>
            <div class="title-container">
                <h1>BUKU CATATAN FASILITAS DAN KEGIATAN</h1>
                <h4>(FACILITY LOG BOOK)</h4>
            </div>
        </header>

        <div class="info-section">
            <div class="section-container">
                <div class="left-column">
                    <table class="info-table">
                        <tr>
                            <td class="info-left">PENYELENGGARA PELAYANAN:</td>
                            <td class="info-center">PERUM LPPNPI CABANG JATSC</td>
                        </tr>
                        <tr>
                            <td class="info-left">KELOMPOK FASILITAS:</td>
                            <td class="info-center">{{ strtoupper($logbook->unit->nama ?? 'UNIT KERJA') }}</td>
                        </tr>
                        <tr>
                            <td class="info-left">NAMA PERALATAN:</td>
                            <td class="info-center">
                                @php
                                    $unique_tools = $logbookItems->pluck('tools')->unique()->filter()->toArray();
                                    $tool_count = count($unique_tools);
                                    $font_size = max(12, 18 - ($tool_count * 1.2));
                                @endphp
                                @foreach($unique_tools as $index => $tool)
                                    <span @style(['font-size: ' . $font_size . 'px;'])>{{ $tool }}</span>
                                    @if($index < count($unique_tools) - 1) / @endif
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="info-left">TANGGAL:</td>
                            <td class="info-center">{{ strtoupper(\Carbon\Carbon::parse($logbook->date)->translatedFormat('d F Y')) }}</td>
                        </tr>
                        <tr>
                            <td class="info-left">SHIFT:</td>
                            <td class="info-center">
                                <input type="checkbox" {{ $logbook->shift == '1' ? 'checked' : '' }} disabled class="shift-checkbox"> P
                                <input type="checkbox" {{ $logbook->shift == '2' ? 'checked' : '' }} disabled class="shift-checkbox"> S
                                <input type="checkbox" {{ $logbook->shift == '3' ? 'checked' : '' }} disabled class="shift-checkbox"> M
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="right-column">
                    <div class="info-right">
                        @php
                            // Mengubah ke fullname
                            $unique_teknisi = $logbookItems->pluck('teknisi_user.fullname')->unique()->filter()->values()->toArray();
                        @endphp
                        @for($i = 0; $i < 5; $i++)
                            <p>
                                <span class="teknisi-container">
                                    <span class="underline">
                                        {{ $i + 1 }}.
                                        @if(isset($unique_teknisi[$i]))
                                            {{ $unique_teknisi[$i] }}
                                        @endif
                                    </span>
                                </span>
                            </p>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        <table class="logbook-table">
            <thead>
                <tr>
                    <th class="no-col">NO.</th>
                    <th class="time-col">TANGGAL/JAM</th>
                    <th class="duration-col">DURASI</th>
                    <th>CATATAN/TINDAKAN</th>
                    <th>NAMA TEKNISI</th>
                    <th class="signature-col">PARAF</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logbookItems as $index => $item)
                <tr>
                    <td class="no-col">{{ $index + 1 }}</td>
                    <td class="time-col">{{ $item->mulai ? \Carbon\Carbon::parse($item->mulai)->format('d/m/Y H:i') : '-' }}</td>
                    <td class="duration-col">
                        @if($item->mulai && $item->selesai)
                            @php
                                $mulai = \Carbon\Carbon::parse($item->mulai);
                                $selesai = \Carbon\Carbon::parse($item->selesai);
                                $diff = $mulai->diff($selesai);
                                $durasi = '';
                                if($diff->d > 0) $durasi .= $diff->d . ' hari ';
                                if($diff->h > 0) $durasi .= $diff->h . ' jam ';
                                if($diff->i > 0) $durasi .= $diff->i . ' menit ';
                                if($diff->s > 0 || empty(trim($durasi))) $durasi .= $diff->s . ' detik';
                                echo trim($durasi);
                            @endphp
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                    {{-- Mengubah ke fullname --}}
                    <td>{{ $item->teknisi_user->fullname ?? 'Unknown' }}</td> 
                    <td class="signature-col">
                        @if($item->teknisi_user && $item->teknisi_user->signature)
                            <img src="{{ $item->teknisi_user->signature }}" alt="Paraf Teknisi" style="width: 50px; height: 25px;">
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                @for($i = 1; $i <= 10; $i++)
                <tr style="height: 40px;">
                    <td class="no-col">{{ $i }}</td>
                    <td class="time-col"></td>
                    <td class="duration-col"></td>
                    <td></td>
                    <td></td>
                    <td class="signature-col"></td>
                </tr>
                @endfor
                @endforelse

                @if(count($logbookItems) > 0 && count($logbookItems) < 10)
                    @for($i = count($logbookItems) + 1; $i <= 10; $i++)
                    <tr style="height: 40px;">
                        <td class="no-col">{{ $i }}</td>
                        <td class="time-col"></td>
                        <td class="duration-col"></td>
                        <td></td>
                        <td></td>
                        <td class="signature-col"></td>
                    </tr>
                    @endfor
                @endif
            </tbody>
        </table>

        <div class="signature-section">
            @if($logbook->is_approved && $logbook->approvedBy)
                <div class="signature-container">
                    Jakarta, {{ \Carbon\Carbon::parse($logbook->date)->translatedFormat('d F Y') }}<br>
                    {{ $logbook->approvedBy->position ?? 'Atasan' }}<br>
                    @if($logbook->approvedBy->signature)
                        <img src="{{ $logbook->approvedBy->signature }}" alt="Tanda Tangan" style="width: 200px; height: auto; margin-top: 10px;"><br>
                    @else
                        <div style="height: 80px; margin-top: 10px;"></div>
                    @endif
                    {{-- Mengubah ke fullname --}}
                    {{ $logbook->approvedBy->fullname }}{{ $logbook->approvedBy->gelar ? ', ' . $logbook->approvedBy->gelar : '' }}
                </div>
            @else
                <div class="signature-container" style="filter: blur(5px); transition: filter 0.3s ease-in-out; user-select: none; pointer-events: none;">
                    Jakarta, {{ \Carbon\Carbon::parse($logbook->date)->translatedFormat('d F Y') }}<br>
                    (Jabatan)<br>
                    <div style="height: 80px; margin-top: 10px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999;">
                        (Tanda Tangan)
                    </div>
                    (Nama TTD)
                </div>
            @endif
        </div>
    </div>

    <script>
        
    </script>
</body>
</html>