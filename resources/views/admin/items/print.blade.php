<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak QR Code - {{ $item->name }}</title>
    <style>
        @page {
            size: 58mm auto;
            margin: 2mm;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
                background: white !important;
                width: 58mm;
            }
            .print-container {
                box-shadow: none !important;
                padding: 3mm !important;
                max-width: 100% !important;
                width: 58mm !important;
            }
            .qrcode-section {
                border: 1px solid #d1d5db !important;
                page-break-inside: avoid;
            }
            .detail-item {
                page-break-inside: avoid;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: white;
            padding: 0;
            line-height: 1.3;
            width: 58mm;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .print-container {
            max-width: 58mm;
            width: 58mm;
            background: white;
            padding: 3mm;
            text-align: center;
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
            padding-bottom: 2mm;
            border-bottom: 2px solid #2563eb;
        }

        .header-icon {
            font-size: 18px;
            margin-bottom: 2px;
            color: #2563eb;
        }

        .header-icon i {
            display: inline-block;
        }

        .header h1 {
            font-size: 10px;
            color: #1e40af;
            margin-bottom: 1px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .header p {
            color: #6b7280;
            font-size: 7px;
            font-weight: 500;
        }

        .qrcode-section {
            text-align: center;
            margin: 2mm 0;
            padding: 2mm;
            background: white;
            border-radius: 2mm;
            border: 1px dashed #cbd5e1;
            position: relative;
            overflow: hidden;
        }

        .qrcode-section::before {
            display: none;
        }

        .qrcode-section img {
            height: 35mm;
            max-width: 100%;
            margin-bottom: 1mm;
            display: block;
            margin-left: auto;
            margin-right: auto;
            object-fit: contain;
        }

        .qrcode-code {
            font-size: 8px;
            font-weight: 700;
            color: #111827;
            letter-spacing: 1px;
            font-family: 'Courier New', monospace;
            background: white;
            display: inline-block;
            padding: 8px 20px;
            border-radius: 6px;
            border: 2px solid #e5e7eb;
            word-break: break-all;
            max-width: 100%;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 3mm;
            margin: 3mm 0;
        }

        .detail-item {
            padding: 2mm;
            background: #ffffff;
            border: none;
            text-align: center;
        }

        .detail-item:hover {
            box-shadow: none;
        }

        .detail-label {
            display: none;
        }

        .detail-value {
            font-size: 10px;
            color: #111827;
            font-weight: 700;
            word-break: break-word;
            text-align: center;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 1mm;
            padding: 1mm 3mm;
            border-radius: 2mm;
            font-size: 8px;
            font-weight: 700;
        }

        .status-badge i {
            font-size: 7px;
        }

        .status-baik {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #10b981;
        }

        .status-rusak-ringan {
            background: #fef3c7;
            color: #92400e;
            border: 2px solid #f59e0b;
        }

        .status-rusak-berat {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #ef4444;
        }

        .footer {
            margin-top: 3mm;
            padding-top: 2mm;
            border-top: 0.3mm solid #e5e7eb;
            text-align: center;
            color: #9ca3af;
            font-size: 6px;
        }

        .footer p {
            margin: 0.5mm 0;
        }

        .print-button {
            position: fixed;
            top: 25px;
            right: 25px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            border: none;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
            transition: all 0.3s;
            z-index: 1000;
        }

        .print-button:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        }

        .print-button i {
            margin-right: 8px;
        }

        /* Print-specific for thermal printer */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .print-button {
                display: none;
            }
        }

        @media screen and (max-width: 360px) {
            .qrcode-section img {
                height: 100px;
            }
            .qrcode-code {
                font-size: 12px;
                letter-spacing: 1px;
                padding: 4px 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">
        <i class="fas fa-print"></i>Cetak QR Code
    </button>

    <div class="print-container">
        <div class="qrcode-section">
            <img src="{{ asset($item->barcode_path) }}" alt="QR Code {{ $item->code }}">
            <div class="qrcode-code">{{ $item->code }}</div>
        </div>

        <div class="details-grid">
            <div class="detail-item full-width">
                <div class="detail-value">{{ $item->name }}</div>
            </div>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
