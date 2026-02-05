{{-- resources/views/admin/attendance/pdf/monthly.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 15px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .info-box {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-item {
            display: table-cell;
            width: 20%;
            text-align: center;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary-item strong {
            display: block;
            font-size: 18px;
            color: #333;
            margin-top: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }
        th {
            background-color: #4a5568;
            color: white;
            padding: 8px 5px;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        td {
            padding: 6px 5px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        td:nth-child(2) {
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .count-box {
            display: inline-block;
            min-width: 30px;
            padding: 3px 6px;
            border-radius: 4px;
            font-weight: bold;
        }
        .count-present {
            background-color: #d4edda;
            color: #155724;
        }
        .count-late {
            background-color: #fff3cd;
            color: #856404;
        }
        .count-permission {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .percentage-bar {
            width: 100%;
            height: 15px;
            background-color: #e5e7eb;
            border-radius: 7px;
            overflow: hidden;
            position: relative;
        }
        .percentage-fill {
            height: 100%;
            text-align: center;
            line-height: 15px;
            color: white;
            font-size: 9px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN REKAP ABSENSI BULANAN</h1>
        <p>{{ \Carbon\Carbon::create($year, $month, 1)->isoFormat('MMMM YYYY') }}</p>
        <p>{{ config('app.name', 'Sistem Jurnal Guru') }}</p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <strong>Periode:</strong> {{ \Carbon\Carbon::create($year, $month, 1)->isoFormat('MMMM YYYY') }}
        </div>
        <div class="info-row">
            <strong>Jumlah Hari Kerja:</strong> {{ $workingDays }} hari
        </div>
        <div class="info-row">
            <strong>Total Guru:</strong> {{ $totalGuru }} orang
        </div>
        <div class="info-row">
            <strong>Dicetak:</strong> {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB
        </div>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span>Total Guru</span>
            <strong style="color: #6366f1;">{{ $totalGuru }}</strong>
        </div>
        <div class="summary-item">
            <span>Rata-rata Hadir</span>
            <strong style="color: #10b981;">{{ number_format($averagePresent, 1) }}%</strong>
        </div>
        <div class="summary-item">
            <span>Rata-rata Terlambat</span>
            <strong style="color: #f59e0b;">{{ number_format($averageLate, 1) }}%</strong>
        </div>
        <div class="summary-item">
            <span>Hari Kerja</span>
            <strong style="color: #3b82f6;">{{ $workingDays }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="22%">Nama Guru</th>
                <th width="12%">NIY</th>
                <th width="8%">Hadir</th>
                <th width="8%">Terlambat</th>
                <th width="7%">Izin</th>
                <th width="12%">Jam Mengajar</th>
                <th width="18%">Persentase Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $index => $data)
                @php
                    $percentage = $workingDays > 0 ? ($data->present_count / $workingDays) * 100 : 0;
                    $fillColor = $percentage >= 90 ? '#10b981' : ($percentage >= 75 ? '#f59e0b' : '#ef4444');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->niy }}</td>
                    <td><span class="count-box count-present">{{ $data->present_count }}</span></td>
                    <td><span class="count-box count-late">{{ $data->late_count }}</span></td>
                    <td><span class="count-box count-permission">{{ $data->permission_count }}</span></td>
                    <td style="text-align: center; font-weight: bold; color: #6366f1;">{{ $data->total_teaching_hours ?? 0 }} JP</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 5px;">
                            <span style="min-width: 40px; font-weight: bold;">{{ number_format($percentage, 1) }}%</span>
                            <div class="percentage-bar" style="flex: 1;">
                                <div class="percentage-fill" style="width: {{ $percentage }}%; background-color: {{ $fillColor }};">
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="6" style="text-align: right; padding-right: 10px;">TOTAL JAM MENGAJAR:</td>
                <td style="text-align: center; color: #6366f1; font-size: 12px;">{{ $monthlyData->sum('total_teaching_hours') }} JP</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <br><br><br>
            <p>___________________________</p>
        </div>
    </div>
</body>
</html>