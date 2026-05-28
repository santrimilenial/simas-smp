<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - {{ \Carbon\Carbon::create()->month((int)$month)->isoFormat('MMMM') }} {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #16a34a;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0 0 5px 0;
            color: #1f2937;
        }
        .header p {
            margin: 2px 0;
            color: #6b7280;
            font-size: 11px;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stats .stat-box {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px 5px;
            border: 1px solid #e5e7eb;
        }
        .stat-box .label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
        }
        .stat-box .value {
            font-size: 14px;
            font-weight: bold;
        }
        .stat-box.income .value { color: #16a34a; }
        .stat-box.expense .value { color: #dc2626; }
        .stat-box.balance .value { color: #7c3aed; }
        .stat-box.total .value { color: #2563eb; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #16a34a;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            border: 1px solid #e5e7eb;
            padding: 6px;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-income {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .badge-expense {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .amount-income { color: #16a34a; font-weight: bold; }
        .amount-expense { color: #dc2626; font-weight: bold; }
        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .summary-table {
            width: 300px;
            margin-left: auto;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 5px 10px;
            font-size: 11px;
        }
        .summary-table .label-cell {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN BULANAN</h1>
        <p>Periode: {{ \Carbon\Carbon::create()->month((int)$month)->isoFormat('MMMM') }} {{ $year }}</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-box total">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $stats['total_records'] }}</div>
        </div>
        <div class="stat-box" style="display:table-cell;width:20%;text-align:center;padding:10px 5px;border:1px solid #e5e7eb;">
            <div class="label">Saldo Awal</div>
            <div class="value" style="font-size:13px;font-weight:bold;color:{{ $stats['carry_over'] >= 0 ? '#b45309' : '#dc2626' }};">Rp {{ number_format($stats['carry_over'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box income">
            <div class="label">Total Pemasukan</div>
            <div class="value">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box expense">
            <div class="label">Total Pengeluaran</div>
            <div class="value">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box balance">
            <div class="label">Saldo Akhir</div>
            <div class="value">Rp {{ number_format($stats['balance'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th style="width: 70px;">Tanggal</th>
                <th class="text-center" style="width: 80px;">Jenis</th>
                <th style="width: 90px;">Kategori</th>
                <th>Deskripsi</th>
                <th class="text-right" style="width: 100px;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $record)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $record->record_date->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $record->type === 'income' ? 'badge-income' : 'badge-expense' }}">
                            {{ $record->type_label }}
                        </span>
                    </td>
                    <td>{{ $record->category }}</td>
                    <td>{{ $record->description }}</td>
                    <td class="text-right {{ $record->type === 'income' ? 'amount-income' : 'amount-expense' }}">
                        {{ $record->type === 'income' ? '+' : '-' }} Rp {{ number_format($record->amount, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px; color: #9ca3af;">
                        Belum ada catatan keuangan untuk periode ini
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    @if($records->count() > 0)
    <table class="summary-table">
        <tr>
            <td class="label-cell">Saldo Awal:</td>
            <td class="text-right" style="font-weight:bold;color:{{ $stats['carry_over'] >= 0 ? '#b45309' : '#dc2626' }};">Rp {{ number_format($stats['carry_over'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label-cell">Total Pemasukan:</td>
            <td class="amount-income text-right">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label-cell">Total Pengeluaran:</td>
            <td class="amount-expense text-right">Rp {{ number_format($stats['total_expense'], 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid #333;">
            <td class="label-cell">Saldo Akhir:</td>
            <td class="text-right" style="font-weight: bold; font-size: 12px; color: {{ $stats['balance'] >= 0 ? '#16a34a' : '#dc2626' }};">
                Rp {{ number_format($stats['balance'], 0, ',', '.') }}
            </td>
        </tr>
    </table>
    @endif

    <!-- Tanda Tangan -->
    <table style="width: 100%; margin-top: 50px; border: none;">
        <tr>
            <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
                <p style="margin: 0; font-size: 11px;">Mengetahui,</p>
                <p style="margin: 0; font-weight: bold; font-size: 11px;">Kepala Sekolah</p>
                <br><br><br><br>
                <p style="margin: 0; font-size: 11px;">(_________________________)</p>
                <p style="margin: 0; font-size: 9px; color: #6b7280;">NIP.</p>
            </td>
            <td style="width: 50%; text-align: center; border: none; vertical-align: top;">
                <p style="margin: 0; font-size: 11px;">&nbsp;</p>
                <p style="margin: 0; font-weight: bold; font-size: 11px;">Bendahara</p>
                <br><br><br><br>
                <p style="margin: 0; font-size: 11px;">(_________________________)</p>
                <p style="margin: 0; font-size: 9px; color: #6b7280;">NIP.</p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini digenerate secara otomatis oleh SIMAS &middot; {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
