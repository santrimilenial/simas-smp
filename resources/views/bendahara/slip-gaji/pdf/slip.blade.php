<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $slipGaji->user->name }} - {{ $slipGaji->period }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 2px solid #333;
        }
        .header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
        }
        .header p {
            margin: 0;
            opacity: 0.9;
        }
        .content {
            padding: 25px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            padding: 8px 0;
            color: #666;
            width: 40%;
        }
        .info-value {
            display: table-cell;
            padding: 8px 0;
            font-weight: bold;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .detail-table td {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-table td:last-child {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background: #f0fdf4;
            padding: 15px;
            margin-top: 15px;
        }
        .total-row table {
            width: 100%;
        }
        .total-row td:first-child {
            font-size: 14px;
            font-weight: bold;
        }
        .total-row td:last-child {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #059669;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }
        .signature-area {
            float: right;
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        .note {
            margin-top: 80px;
            font-size: 10px;
            color: #666;
            font-style: italic;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-draft {
            background: #fef3c7;
            color: #92400e;
        }
        .status-approved {
            background: #dbeafe;
            color: #1e40af;
        }
        .status-paid {
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>SLIP GAJI</h1>
            <p>Periode: {{ $slipGaji->period }}</p>
        </div>

        <div class="content">
            <!-- Teacher Info -->
            <div class="section">
                <div class="section-title">Data Guru</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $slipGaji->user->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">NIY</div>
                        <div class="info-value">{{ $slipGaji->user->niy }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $slipGaji->user->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="status-badge status-{{ $slipGaji->status }}">{{ $slipGaji->status_label }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Details -->
            <div class="section">
                <div class="section-title">Rincian Gaji</div>
                <table class="detail-table">
                    <tr>
                        <td>Total Jam Mengajar</td>
                        <td>{{ $slipGaji->total_teaching_hours }} Jam</td>
                    </tr>
                    <tr>
                        <td>Rate per Jam</td>
                        <td>{{ $slipGaji->formatted_rate }}</td>
                    </tr>
                    <tr>
                        <td>Perhitungan</td>
                        <td>{{ $slipGaji->total_teaching_hours }} x {{ $slipGaji->formatted_rate }}</td>
                    </tr>
                </table>

                <div class="total-row">
                    <table>
                        <tr>
                            <td>TOTAL GAJI</td>
                            <td>{{ $slipGaji->formatted_total }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="signature-area">
                    <p>{{ now()->isoFormat('D MMMM YYYY') }}</p>
                    <p>Bendahara,</p>
                    <div class="signature-line"></div>
                    <p>{{ $slipGaji->creator->name }}</p>
                </div>
                <div class="note">
                    Slip gaji ini dibuat secara elektronik dan sah tanpa tanda tangan basah.<br>
                    Dicetak pada: {{ now()->isoFormat('D MMMM YYYY, HH:mm') }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
