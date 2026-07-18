<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logbook - {{ $logbook->judul }}</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .logbook-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .info-group {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        .info-value {
            padding: 8px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 3px;
            min-height: 35px;
        }

        .content-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 8px;
            background: #e9ecef;
            border-left: 4px solid #007bff;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .items-table th {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
        }

        .items-table .no-col {
            width: 40px;
            text-align: center;
        }

        .items-table .time-col {
            width: 80px;
        }

        .items-table .duration-col {
            width: 100px;
        }

        .empty-row {
            height: 40px;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 30px;
            margin-top: 40px;
            text-align: center;
        }

        .signature-box {
            border: 1px solid #333;
            padding: 15px;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-line {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
            font-size: 11px;
        }

        .print-info {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-top: 20px;
        }

        .btn-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }

        .btn-print:hover {
            background: #0056b3;
        }

        .shift-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .shift-pagi {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .shift-siang {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .shift-malam {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>
<body>
    <button class="btn-print no-print" onclick="window.print()">
        🖨️ Print Logbook
    </button>

    <div class="container">
        <div class="header">
            <h1>LOGBOOK KEGIATAN</h1>
            <h2>{{ strtoupper($logbook->unit->nama ?? 'UNIT KERJA') }}</h2>
        </div>

        <div class="logbook-info">
            <div class="info-group">
                <div class="info-label">Judul Logbook:</div>
                <div class="info-value">{{ $logbook->judul }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Tanggal Kegiatan:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($logbook->date)->format('d F Y') }}</div>
            </div>
            <div class="info-group">
                <div class="info-label">Shift:</div>
                <div class="info-value">
                    @if($logbook->shift == '1')
                        <span class="shift-badge shift-pagi">PAGI</span>
                    @elseif($logbook->shift == '2')
                        <span class="shift-badge shift-siang">SIANG</span>
                    @elseif($logbook->shift == '3')
                        <span class="shift-badge shift-malam">MALAM</span>
                    @endif
                </div>
            </div>
            <div class="info-group">
                <div class="info-label">Status:</div>
                <div class="info-value">
                    @if($logbook->is_approved == 0)
                        <span class="status-badge status-pending">PENDING</span>
                    @else
                        <span class="status-badge status-approved">APPROVED</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="section-title">DETAIL KEGIATAN</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="no-col">No</th>
                        <th>Kegiatan</th>
                        <th class="time-col">Mulai</th>
                        <th class="time-col">Selesai</th>
                        <th class="duration-col">Durasi</th>
                        <th>Teknisi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logbookItems as $index => $item)
                    <tr>
                        <td class="no-col">{{ $index + 1 }}</td>
                        <td>{{ $item->kegiatan ?? '-' }}</td>
                        <td>{{ $item->mulai ? \Carbon\Carbon::parse($item->mulai)->format('H:i') : '-' }}</td>
                        <td>{{ $item->selesai ? \Carbon\Carbon::parse($item->selesai)->format('H:i') : '-' }}</td>
                        <td>{{ $durations[$index] ?? '-' }}</td>
                        <td>{{ $teknisi[$index] ?? '-' }}</td>
                        <td>{{ $item->catatan ?? '-' }}</td>
                    </tr>
                    @empty
                    @for($i = 1; $i <= 10; $i++)
                    <tr class="empty-row">
                        <td class="no-col">{{ $i }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor
                    @endforelse

                    @if(count($logbookItems) > 0 && count($logbookItems) < 10)
                        @for($i = count($logbookItems) + 1; $i <= 10; $i++)
                        <tr class="empty-row">
                            <td class="no-col">{{ $i }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">DIBUAT OLEH</div>
                <div class="signature-line">
                    {{ $logbook->createdBy->name ?? 'N/A' }}<br>
                    <small>{{ \Carbon\Carbon::parse($logbook->created_at)->format('d/m/Y H:i') }}</small>
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-title">DISETUJUI OLEH</div>
                <div class="signature-line">
                    @if($logbook->is_approved && $logbook->approvedBy)
                        {{ $logbook->approvedBy->name }}<br>
                        <small>{{ $logbook->signed_at ? \Carbon\Carbon::parse($logbook->signed_at)->format('d/m/Y H:i') : 'Belum ditandatangani' }}</small>
                    @else
                        <small>Belum disetujui</small>
                    @endif
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-title">MENGETAHUI</div>
                <div class="signature-line">
                    @if($logbook->signedBy)
                        {{ $logbook->signedBy->name }}<br>
                        <small>{{ $logbook->signed_at ? \Carbon\Carbon::parse($logbook->signed_at)->format('d/m/Y H:i') : '' }}</small>
                    @else
                        <small>Belum ditandatangani</small>
                    @endif
                </div>
            </div>
        </div>

        <div class="print-info">
            Dicetak pada: {{ now()->format('d F Y H:i:s') }} | 
            ID Logbook: {{ $logbook->id }}
        </div>
    </div>
</body>
</html>
