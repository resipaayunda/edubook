<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman EduBook</title>
    <style>
        /* Desain Standar Dokumen PDF */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11pt;
            color: #333;
            line-height: 1.5;
        }

        /* Header Laporan */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4364F7;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            color: #0052D4;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            font-size: 10pt;
            color: #666;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #4364F7;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 10pt;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
            font-size: 10pt;
            vertical-align: top;
        }

        /* Warna Zebra pada Baris */
        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* Status Styling (Manual karena PDF tidak support CSS Bootstrap) */
        .status-kembali {
            color: #15803d;
            font-weight: bold;
        }

        .status-dipinjam {
            color: #b45309;
            font-weight: bold;
        }

        /* Footer Cetak */
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 9pt;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Laporan Peminjaman EduBook</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tgl Pinjam</th>
                <th width="20%">Peminjam</th>
                <th width="30%">Judul Buku</th>
                <th width="15%">Tgl Kembali</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $i => $l)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($l->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ $l->user->name ?? 'Guest' }}</td>
                <td>{{ $l->buku }}</td> {{-- Ini kolom teks dari DB kamu --}}
                <td>{{ $l->tanggal_kembali ?? '-' }}</td>
                <td>
                    @if($l->status == 'kembali')
                        <span class="status-kembali">Selesai</span>
                    @else
                        <span class="status-dipinjam">Dipinjam</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data laporan ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem EduBook.</p>
    </div>

</body>
</html>