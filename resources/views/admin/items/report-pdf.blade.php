<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #333;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #666;
        }
        .summary {
            margin-bottom: 15px;
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .summary-item .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
        }
        .summary-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-top: 3px;
        }
        .filters {
            margin-bottom: 10px;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #2563eb;
            color: white;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #1d4ed8;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        table td {
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 9px;
            vertical-align: top;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge-baik {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
        .badge-ringan {
            background-color: #fef9c3;
            color: #854d0e;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
        .badge-berat {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #999;
        }
        .total-row {
            background-color: #f0f0f0 !important;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN INVENTARIS BARANG</h1>
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
        @if($filters['condition'] || $filters['category'] || $filters['location'] || $filters['search'])
        <p style="font-size: 10px; margin-top: 5px;">
            Filter:
            @if($filters['condition']) Kondisi: {{ ucfirst($filters['condition']) }} @endif
            @if($filters['category']) | Kategori: {{ $filters['category'] }} @endif
            @if($filters['location']) | Lokasi: {{ $filters['location'] }} @endif
            @if($filters['search']) | Pencarian: "{{ $filters['search'] }}" @endif
        </p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Barang</div>
            <div class="value">{{ $totalItems }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Unit</div>
            <div class="value">{{ number_format($totalQuantity) }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Baik</div>
            <div class="value" style="color: #166534;">{{ $conditionStats['baik'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Rusak Ringan</div>
            <div class="value" style="color: #854d0e;">{{ $conditionStats['rusak_ringan'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Rusak Berat</div>
            <div class="value" style="color: #991b1b;">{{ $conditionStats['rusak_berat'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Nilai Aset</div>
            <div class="value">Rp {{ number_format($totalValue, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="7%">Kode</th>
                <th width="18%">Nama Barang</th>
                <th width="10%">Kategori</th>
                <th width="12%">Lokasi</th>
                <th width="8%">Kondisi</th>
                <th width="5%">Jml</th>
                <th width="12%">Harga Satuan</th>
                <th width="12%">Total Nilai</th>
                <th width="8%">Tgl Beli</th>
                <th width="5%">Ket</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category ?? '-' }}</td>
                <td>{{ $item->location ?? '-' }}</td>
                <td class="text-center">
                    @if($item->condition === 'baik')
                        <span class="badge-baik">Baik</span>
                    @elseif($item->condition === 'rusak ringan')
                        <span class="badge-ringan">Rusak Ringan</span>
                    @else
                        <span class="badge-berat">Rusak Berat</span>
                    @endif
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ $item->price ? 'Rp ' . number_format($item->price, 0, ',', '.') : '-' }}</td>
                <td class="text-right">{{ $item->price ? 'Rp ' . number_format($item->price * $item->quantity, 0, ',', '.') : '-' }}</td>
                <td>{{ $item->purchase_date?->format('d/m/Y') ?? '-' }}</td>
                <td>{{ $item->description ? Str::limit($item->description, 20) : '-' }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-right" style="padding: 8px 5px;">TOTAL</td>
                <td class="text-center" style="padding: 8px 5px;">{{ number_format($items->sum('quantity')) }}</td>
                <td style="padding: 8px 5px;">-</td>
                <td class="text-right" style="padding: 8px 5px;">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
                <td colspan="2" style="padding: 8px 5px;"></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan Inventaris Barang - {{ config('app.name') }}</p>
    </div>
</body>
</html>
