<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11pt; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4364F7; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #0052D4; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #4364F7; color: white; padding: 10px; text-align: left; font-size: 10pt; }
        td { padding: 8px 10px; border-bottom: 1px solid #ddd; font-size: 10pt; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .status-kembali { color: #15803d; font-weight: bold; }
        .status-dipinjam { color: #b45309; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 9pt; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Riwayat Peminjaman: {{ auth()->user()->name }}</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Tgl Pinjam</th>
                <th width="40%">Nama Barang</th>
                <th width="20%">Tgl Kembali</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $i => $l)
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($l->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ $l->buku }}</td>
                <td>{{ $l->tanggal_kembali ?? '-' }}</td>
                <td>{{ $l->status == 'kembali' ? 'Selesai' : 'Dipinjam' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>