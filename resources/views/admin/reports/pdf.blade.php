<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jurnal Mengajar</title>
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
        .info {
            margin-bottom: 15px;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #f0f0f0;
            padding: 8px 5px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 10px;
        }
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
            vertical-align: top;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .badge {
            background-color: #e8f5e9;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN JURNAL MENGAJAR GURU</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        @if(isset($filterClass) && $filterClass)
        <p>Kelas: {{ $filterClass }}</p>
        @endif
        <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="info">
        <strong>Total Jurnal:</strong> {{ $logs->count() }} entri
        @php
            $academicYears = $logs->filter(fn($log) => $log->academicYear)->pluck('academicYear.full_name')->unique()->implode(', ');
        @endphp
        @if($academicYears)
        <br><strong>Tahun Ajaran:</strong> {{ $academicYears }}
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="8%">Tanggal</th>
                <th width="13%">Guru</th>
                <th width="9%">NIY</th>
                <th width="10%">Mapel</th>
                <th width="6%">Kelas</th>
                <th width="5%">Pert.</th>
                <th width="5%">Jam</th>
                <th width="20%">Tujuan Pembelajaran</th>
                <th width="18%">Materi</th>
                <th width="3%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $index => $log)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $log->log_date->format('d/m/Y') }}</td>
                <td>{{ $log->user->name }}</td>
                <td>{{ $log->user->niy }}</td>
                <td>{{ $log->subject }}</td>
                <td style="text-align: center;">{{ $log->class }}</td>
                <td style="text-align: center;"><span class="badge">{{ $log->meeting_number }}</span></td>
                <td style="text-align: center;">{{ $log->time_slot }}</td>
                <td>{{ $log->tp }}</td>
                <td>{{ Str::limit($log->material, 80) }}</td>
                <td>{{ $log->notes ? Str::limit($log->notes, 30) : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Aplikasi Jurnal Mengajar Guru</p>
    </div>
</body>
</html>