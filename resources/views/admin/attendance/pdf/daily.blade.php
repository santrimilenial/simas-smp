{{-- resources/views/admin/attendance/pdf/daily.blade.php --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Absensi Harian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .info-box {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #4a5568;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .status-present {
            background-color: #d4edda;
            color: #155724;
        }
        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-permission {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .status-sick {
            background-color: #f5c6cb;
            color: #721c24;
        }
        .timely-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
        }
        .timely-on-time {
            background-color: #d4edda;
            color: #155724;
        }
        .timely-late {
            background-color: #fff3cd;
            color: #856404;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ABSENSI HARIAN</h1>
        <p>{{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM YYYY') }}</p>
        <p>{{ config('app.name', 'Sistem Jurnal Guru') }}</p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <span><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($date)->isoFormat('D MMMM YYYY') }}</span>
            <span><strong>Dicetak:</strong> {{ now()->isoFormat('D MMMM YYYY, HH:mm') }} WIB</span>
        </div>
        <div class="info-row">
            <span><strong>Total Guru:</strong> {{ $attendances->count() }} orang</span>
        </div>
    </div>

    @if($attendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="4%">No</th>
                    <th width="20%">Nama Guru</th>
                    <th width="11%">NIY</th>
                    <th width="10%">Check In</th>
                    <th width="13%">Status Berangkat</th>
                    <th width="10%">Check Out</th>
                    <th width="20%">Keterangan Izin/Sakit</th>
                    <th width="12%">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attendances as $index => $attendance)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $attendance->user->name }}</td>
                        <td>{{ $attendance->user->niy }}</td>
                        <td style="text-align: center;">{{ $attendance->formatted_check_in ?? '-' }}</td>
                        <td style="text-align: center;">
                            @if($attendance->check_in_status === 'present')
                                @php
                                    $settings = \App\Models\AttendanceSetting::current();
                                    $checkInTime = $attendance->check_in_time;
                                    $lateTime = \Carbon\Carbon::parse($settings->actual_late_time ?? '07:00');
                                    $isLate = $checkInTime->greaterThan($lateTime);
                                @endphp
                                @if($isLate)
                                    <span class="timely-status timely-late">
                                        Terlambat {{ round($checkInTime->diffInMinutes($lateTime)) }}m
                                    </span>
                                @else
                                    <span class="timely-status timely-on-time">
                                        Tepat Waktu
                                    </span>
                                @endif
                            @else
                                <span class="status status-{{ $attendance->check_in_status === 'permission' ? 'permission' : 'sick' }}">
                                    {{ $attendance->check_in_status_label }}
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $attendance->formatted_check_out ?? '-' }}</td>
                        <td style="font-size: 9px;">
                            @if(in_array($attendance->check_in_status, ['permission', 'sick']))
                                {{ $attendance->check_in_reason ?? '-' }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="font-size: 9px;">{{ $attendance->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            Tidak ada data absensi pada tanggal ini
        </div>
    @endif

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,<br>Kepala Sekolah</p>
            <br><br><br>
            <p>___________________________</p>
        </div>
    </div>
</body>
</html>