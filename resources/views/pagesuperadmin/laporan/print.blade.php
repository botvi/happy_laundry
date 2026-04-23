<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - Happy Laundry</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #dc653d; padding-bottom: 12px; }
        .header h1 { font-size: 20px; color: #dc653d; margin-bottom: 4px; }
        .header p { color: #666; font-size: 11px; }
        .summary { display: flex; gap: 20px; margin-bottom: 20px; }
        .summary-box { border: 1px solid #ddd; border-radius: 6px; padding: 10px 16px; flex: 1; }
        .summary-box p { color: #888; font-size: 10px; margin-bottom: 2px; }
        .summary-box h3 { font-size: 15px; color: #dc653d; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #dc653d; color: white; padding: 7px 8px; text-align: left; font-size: 11px; }
        td { padding: 6px 8px; border-bottom: 1px solid #eee; font-size: 11px; }
        tr:nth-child(even) td { background-color: #fdf6f3; }
        .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #888; }
        @media print { button { display: none; } }
    </style>
</head>
<body>
    <div class="header">
        <h1>Happy Laundry</h1>
        <p>Laporan Pesanan — Dicetak pada {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <div class="summary">
        <div class="summary-box">
            <p>Total Pesanan</p>
            <h3>{{ $pesanans->count() }}</h3>
        </div>
        <div class="summary-box">
            <p>Pesanan Selesai</p>
            <h3>{{ $pesanans->where('status_pesanan', 'selesai')->count() }}</h3>
        </div>
        <div class="summary-box">
            <p>Total Pendapatan</p>
            <h3>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Berat (Kg)</th>
                <th>Ongkir</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesanans as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->pelanggan->user->name ?? '-' }}</td>
                <td>{{ $p->paketLaundry->nama_paket ?? '-' }}</td>
                <td>{{ $p->jumlah_kilogram ?? '-' }}</td>
                <td>{{ $p->ongkir_antar_jemput ? 'Rp '.number_format($p->ongkir_antar_jemput,0,',','.') : '-' }}</td>
                <td>{{ $p->total_harga ? 'Rp '.number_format($p->total_harga,0,',','.') : '-' }}</td>
                <td>{{ strtoupper(str_replace('_', ' ', $p->status_pesanan)) }}</td>
                <td>{{ $p->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:12px;color:#888;">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Happy Laundry &copy; {{ date('Y') }}</div>

    <script>window.onload = function() { window.print(); }</script>
</body>
</html>
