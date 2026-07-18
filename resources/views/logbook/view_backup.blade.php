{{-- resources/views/logbook/view.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }} - View ({{ $logbook->judul }})</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/branding/logo-small.png') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
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
        }

        .title-container {
            flex-grow: 1;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        h4 {
            margin: 0;
            margin-top: 5px;
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

        .info-section {
            margin: 0 auto;
            width: 95%;
        }

        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        tr:nth-child(even) {
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

        .print-button, .close-button {
            margin-left: 20px;
        }

        @media print {
            .print-button, .close-button {
                display: none !important;
            }

            body {
                margin: 0 !important;
                font-size: 12px;
            }

            table {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    @auth
    {{-- Konten ini hanya bisa diakses oleh yang sudah login --}}
    <nav class="navbar">
        <div class="container">
            <div class="navbar-header">
                <button class="close-button" onclick="closeWindow()">X</button>
                <span>{{ config('app.name') }} - View ({{ $logbook->judul }})</span>
                <button class="btn btn-primary print-button" onclick="window.print()">Print Logbook</button>
                <button class="btn btn-primary print-button" onclick="generatePDF()">Generate PDF</button>
            </div>
        </div>
    </nav>

    <header>
        <div class="logo"><img src="{{ asset('assets/img/branding/logo-small.png') }}" style="width: 70px; height: 70px;" alt="Logo"></div>
        <div class="title-container">
            <h1>BUKU CATATAN FASILITAS DAN KEGIATAN</h1>
            <h4>(FACILITY LOG BOOK)</h4>
        </div>
    </header>

    <div class="info-section">
        <div class="section-container">
            <div class="left-column">
                <table>
                    <tr>
                        <td>PENYELENGGARA PELAYANAN:</td>
                        <td>PERUM LPPNPI CABANG JATSC</td>
                    </tr>
                    <tr>
                        <td>KELOMPOK FASILITAS:</td>
                        <td>{{ strtoupper($unit_id) }}</td>
                    </tr>
                    <tr>
                        <td>NAMA PERALATAN:</td>
                        <td>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>TANGGAL:</td>
                        <td>{{ \Carbon\Carbon::parse($logbook->tanggal_kegiatan)->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
                    <tr>
                        <td>SHIFT:</td>
                        <td>{{ $radio_shift }}</td>
                    </tr>
                </table>
            </div>
            <div class="right-column">
                <div>
                    @foreach ($teknisi as $index => $nama_teknisi)
                        <p>{{ $index + 1 }}. <span class="underline">{{ $nama_teknisi }}</span></p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <table>
        <tr>
            <th>NO.</th>
            <th>TANGGAL/JAM</th>
            <th>DURASI</th>
            <th>CATATAN/TINDAKAN</th>
            <th>NAMA TEKNISI</th>
            <th>PARAF</th>
        </tr>
        @foreach ($logbookItems as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->mulai }}</td>
            <td>{{ $durations[$index] }}</td>
            <td>{{ $item->uraian }}</td>
            <td>{{ $teknisi[$index] }}</td>
            <td><img src="{{ $item->teknisi->signature }}" alt="Paraf Teknisi" style="width: 50%; height: 50%"></td>
        </tr>
        @endforeach
    </table>

    <div class="signature-section">
        @if ($approve_id)
        <div class="signature-container">
            <p>Jakarta, {{ \Carbon\Carbon::parse($logbook->tanggal_kegiatan)->isoFormat('D MMMM YYYY') }}</p>
            <p>{{ $approve_id->position }}</p>
            <img src="{{ $approve_id->signature }}" alt="Tanda Tangan" style="width: 200px; margin-top: 10px;">
            <p>{{ $approve_id->name }} {{ $approve_id->gelar }}</p>
        </div>
        @else
        <div class="signature-container" style="filter: blur(10px);">
            <p>Jakarta, {{ \Carbon\Carbon::parse($logbook->tanggal_kegiatan)->isoFormat('D MMMM YYYY') }}</p>
            <p>(Jabatan)</p>
            <img src="{{ asset('path/to/default/ttd.jpg') }}" alt="Tanda Tangan" style="width: 200px;">
            <p>(Nama TTD)</p>
        </div>
        @endif
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.2/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF() {
            const element = document.body;
            const opt = {
                margin: 10,
                filename: 'logbook.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { format: 'letter', orientation: 'portrait' }
            };
            html2pdf().from(element).set(opt).save();
        }

        function closeWindow() {
            window.close();
        }
    </script>
    @endauth
</body>
</html>