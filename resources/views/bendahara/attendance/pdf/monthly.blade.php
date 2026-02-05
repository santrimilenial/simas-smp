<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Absensi Bulanan - {{ \Carbon\Carbon::create()->month($month)->isoFormat('MMMM') }} {{ $year }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #1a365d;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-box span {
            margin-right: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2d3748;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .summary-row {
            background-color: #e2e8f0 !important;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REKAP ABSENSI BULANAN GURU</h1>
        <p>Periode: {{ \Carbon\Carbon::create()->month($month)->isoFormat('MMMM') }} {{ $year }}</p>
    </div>

    <div class="info-box">
        <span><strong>Hari Kerja:</strong> {{ $workingDays }} hari</span>
        <span><strong>Total Jam Mengajar:</strong> {{ $totalTeachingHours }} jam</span>
        <span><strong>Estimasi Total Gaji:</strong> Rp {{ number_format($totalEstimatedSalary, 0, ',', '.') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Nama Guru</th>
                <th>NIY</th>
                <th class="text-center">Hadir</th>
                <th class="text-center">Terlambat</th>
                <th class="text-center">Jam Mengajar</th>
                <th class="text-right">Estimasi Gaji</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->niy }}</td>
                    <td class="text-center">{{ $data->present_count }}</td>
                    <td class="text-center">{{ $data->late_count }}</td>
                    <td class="text-center">{{ $data->total_teaching_hours ?? 0 }}</td>
                    <td class="text-right">Rp {{ number_format(($data->total_teaching_hours ?? 0) * 10000, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="summary-row">
                <td colspan="5" class="text-right">Total:</td>
                <td class="text-center">{{ $totalTeachingHours }}</td>
                <td class="text-right">Rp {{ number_format($totalEstimatedSalary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->isoFormat('dddd, D MMMM YYYY HH:mm') }}</p>
        <div class="signature">
            <p>Bendahara,</p>
            <br><br><br>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>
</body>
</html>
