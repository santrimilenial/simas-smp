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
            background: #f3f4f6;
            padding: 0;
            line-height: 1.3;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 20px;
        }

        .page-wrapper {
            display: flex;
            gap: 24px;
            align-items: flex-start;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .print-container {
            max-width: 58mm;
            width: 58mm;
            background: white;
            padding: 3mm;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
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

        /* Thermal Printer Panel */
        .thermal-panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            padding: 24px;
            width: 320px;
            max-width: 90vw;
        }

        .thermal-panel h2 {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .thermal-panel h2 i {
            color: #7c3aed;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            border: 2px solid transparent;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            width: 100%;
        }

        .btn i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            flex-shrink: 0;
        }

        .btn-info {
            font-size: 11px;
            font-weight: 400;
            color: #6b7280;
            display: block;
            margin-top: 2px;
        }

        .btn-print-dialog {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }
        .btn-print-dialog:hover {
            background: #dbeafe;
            border-color: #93c5fd;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37,99,235,0.15);
        }

        .btn-usb {
            background: #f5f3ff;
            color: #6d28d9;
            border-color: #ddd6fe;
        }
        .btn-usb:hover {
            background: #ede9fe;
            border-color: #c4b5fd;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(109,40,217,0.15);
        }

        .btn-bluetooth {
            background: #ecfeff;
            color: #0e7490;
            border-color: #a5f3fc;
        }
        .btn-bluetooth:hover {
            background: #cffafe;
            border-color: #67e8f9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(14,116,144,0.15);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        .btn .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid currentColor;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .btn.loading .spinner {
            display: inline-block;
        }

        .btn.loading .btn-icon {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Status toast */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            z-index: 9999;
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            max-width: 90vw;
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
        }

        .toast-success {
            background: #059669;
            color: white;
        }

        .toast-error {
            background: #dc2626;
            color: white;
        }

        .toast-info {
            background: #2563eb;
            color: white;
        }

        /* Settings section */
        .settings-section {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .settings-section label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .settings-section select,
        .settings-section input {
            width: 100%;
            padding: 8px 12px;
            border: 1.5px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            background: white;
            margin-bottom: 10px;
        }

        .settings-section select:focus,
        .settings-section input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124,58,237,0.1);
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
            padding: 8px 12px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .connection-status .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #d1d5db;
            flex-shrink: 0;
        }

        .connection-status .dot.connected {
            background: #10b981;
            box-shadow: 0 0 6px rgba(16,185,129,0.5);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6b7280;
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #374151;
        }

        /* Print-specific for thermal printer */
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white !important;
                width: 58mm;
                display: block;
            }
            .page-wrapper {
                display: block;
                padding: 0;
            }
            .thermal-panel,
            .back-link {
                display: none !important;
            }
            .print-container {
                box-shadow: none;
                border-radius: 0;
            }
        }

        @media screen and (max-width: 600px) {
            .page-wrapper {
                flex-direction: column;
                align-items: center;
            }
            .thermal-panel {
                order: -1;
                width: 100%;
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
    <div class="page-wrapper no-print-wrapper">
        {{-- Print Preview --}}
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

        {{-- Thermal Printer Panel --}}
        <div class="thermal-panel no-print">
            <a href="{{ route('admin.items.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Kembali ke Inventory
            </a>

            <h2><i class="fas fa-receipt"></i> Cetak QR Code</h2>

            <div class="btn-group">
                {{-- Print Dialog --}}
                <button onclick="window.print()" class="btn btn-print-dialog">
                    <i class="fas fa-print btn-icon"></i>
                    <div>
                        Cetak (Print Dialog)
                        <span class="btn-info">Pilih printer dari dialog browser</span>
                    </div>
                </button>

                {{-- USB Thermal --}}
                <button onclick="printViaThermal('usb')" id="btn-usb" class="btn btn-usb">
                    <i class="fas fa-usb btn-icon"></i>
                    <span class="spinner"></span>
                    <div>
                        Cetak via USB Thermal
                        <span class="btn-info">Kirim langsung ke printer USB (Chrome/Edge)</span>
                    </div>
                </button>

                {{-- Bluetooth Thermal --}}
                <button onclick="printViaThermal('bluetooth')" id="btn-bluetooth" class="btn btn-bluetooth">
                    <i class="fab fa-bluetooth-b btn-icon"></i>
                    <span class="spinner"></span>
                    <div>
                        Cetak via Bluetooth
                        <span class="btn-info">Untuk printer thermal Bluetooth</span>
                    </div>
                </button>
            </div>

            {{-- Settings --}}
            <div class="settings-section">
                <label for="paper-size">
                    <i class="fas fa-ruler-horizontal"></i> Ukuran Kertas
                </label>
                <select id="paper-size" onchange="saveSetting('paperSize', this.value)">
                    <option value="58">58mm (Default)</option>
                    <option value="80">80mm</option>
                </select>

                <label for="qr-size">
                    <i class="fas fa-qrcode"></i> Ukuran QR Code
                </label>
                <select id="qr-size" onchange="saveSetting('qrSize', this.value)">
                    <option value="4">Kecil</option>
                    <option value="6" selected>Sedang (Default)</option>
                    <option value="8">Besar</option>
                    <option value="10">Sangat Besar</option>
                </select>

                <div class="connection-status" id="connection-status">
                    <span class="dot" id="status-dot"></span>
                    <span id="status-text">Belum terhubung</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast notification --}}
    <div class="toast" id="toast"></div>

    <script>
        // Item data
        const ITEM_CODE = @json($item->code);
        const ITEM_NAME = @json($item->name);
        const ITEM_LOCATION = @json($item->location ?? '');

        // Saved settings
        function saveSetting(key, value) {
            localStorage.setItem('thermal_' + key, value);
        }

        function loadSettings() {
            const paperSize = localStorage.getItem('thermal_paperSize');
            const qrSize = localStorage.getItem('thermal_qrSize');
            if (paperSize) document.getElementById('paper-size').value = paperSize;
            if (qrSize) document.getElementById('qr-size').value = qrSize;
        }

        loadSettings();

        // Toast notification
        function showToast(message, type = 'info') {
            const toast = document.getElementById('toast');
            const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle' };
            toast.className = 'toast toast-' + type;
            toast.innerHTML = '<i class="fas ' + icons[type] + '"></i> ' + message;
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => toast.classList.remove('show'), 4000);
        }

        // Connection status
        function setStatus(connected, text) {
            document.getElementById('status-dot').className = 'dot' + (connected ? ' connected' : '');
            document.getElementById('status-text').textContent = text;
        }

        // ===========================
        // ESC/POS Command Builder
        // ===========================
        class EscPosBuilder {
            constructor() {
                this.commands = [];
            }

            raw(...bytes) {
                this.commands.push(...bytes);
                return this;
            }

            init() {
                return this.raw(0x1B, 0x40); // ESC @
            }

            center() {
                return this.raw(0x1B, 0x61, 0x01); // ESC a 1
            }

            left() {
                return this.raw(0x1B, 0x61, 0x00); // ESC a 0
            }

            bold(on = true) {
                return this.raw(0x1B, 0x45, on ? 0x01 : 0x00); // ESC E n
            }

            textSize(w = 1, h = 1) {
                return this.raw(0x1D, 0x21, ((w - 1) << 4) | (h - 1)); // GS ! n
            }

            text(str) {
                const encoded = new TextEncoder().encode(str);
                this.commands.push(...encoded);
                return this;
            }

            newline(n = 1) {
                for (let i = 0; i < n; i++) this.commands.push(0x0A);
                return this;
            }

            separator(paperSize = 58) {
                const count = paperSize === 80 ? 48 : 32;
                return this.text('-'.repeat(count)).newline();
            }

            qrCode(data, moduleSize = 6) {
                const encoded = new TextEncoder().encode(data);
                const len = encoded.length;

                // Select QR model 2
                this.raw(0x1D, 0x28, 0x6B, 0x04, 0x00, 0x31, 0x41, 0x32, 0x00);

                // Set module size
                this.raw(0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x43, moduleSize);

                // Set error correction level H
                this.raw(0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x45, 0x33);

                // Store QR data
                const storeLen = len + 3;
                this.raw(0x1D, 0x28, 0x6B, storeLen & 0xFF, (storeLen >> 8) & 0xFF, 0x31, 0x50, 0x30);
                this.commands.push(...encoded);

                // Print QR code
                this.raw(0x1D, 0x28, 0x6B, 0x03, 0x00, 0x31, 0x51, 0x30);

                return this;
            }

            feed(lines = 3) {
                return this.raw(0x1B, 0x64, lines); // ESC d n
            }

            cut() {
                return this.raw(0x1D, 0x56, 0x41, 0x03); // GS V A 3
            }

            build() {
                return new Uint8Array(this.commands);
            }
        }

        // ===========================
        // USB Thermal Printer
        // ===========================
        class UsbPrinter {
            constructor() {
                this.device = null;
                this.endpointNumber = null;
                this.interfaceNumber = null;
            }

            async connect() {
                this.device = await navigator.usb.requestDevice({ filters: [] });
                await this.device.open();

                if (!this.device.configuration) {
                    await this.device.selectConfiguration(1);
                }

                let found = false;
                for (const iface of this.device.configuration.interfaces) {
                    for (const alt of iface.alternates) {
                        for (const ep of alt.endpoints) {
                            if (ep.direction === 'out') {
                                this.interfaceNumber = iface.interfaceNumber;
                                this.endpointNumber = ep.endpointNumber;
                                found = true;
                                break;
                            }
                        }
                        if (found) break;
                    }
                    if (found) break;
                }

                if (!found) throw new Error('Endpoint output tidak ditemukan pada perangkat USB');

                await this.device.claimInterface(this.interfaceNumber);
                setStatus(true, 'Terhubung via USB: ' + (this.device.productName || 'Thermal Printer'));
            }

            async send(data) {
                const CHUNK = 512;
                for (let i = 0; i < data.length; i += CHUNK) {
                    const chunk = data.slice(i, Math.min(i + CHUNK, data.length));
                    await this.device.transferOut(this.endpointNumber, chunk);
                }
            }

            async disconnect() {
                try {
                    if (this.device) {
                        await this.device.releaseInterface(this.interfaceNumber);
                        await this.device.close();
                    }
                } catch (e) { /* ignore */ }
                this.device = null;
                setStatus(false, 'Terputus');
            }
        }

        // ===========================
        // Bluetooth Thermal Printer
        // ===========================
        class BluetoothPrinter {
            constructor() {
                this.device = null;
                this.characteristic = null;
            }

            async connect() {
                // Common thermal printer BLE service UUIDs
                const serviceUuids = [
                    '000018f0-0000-1000-8000-00805f9b34fb',
                    'e7810a71-73ae-499d-8c15-faa9aef0c3f2',
                    '49535343-fe7d-4ae5-8fa9-9fafd205e455',
                ];

                this.device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: serviceUuids,
                });

                const server = await this.device.gatt.connect();

                // Try to find a writable characteristic
                let foundChar = null;
                for (const uuid of serviceUuids) {
                    try {
                        const service = await server.getPrimaryService(uuid);
                        const chars = await service.getCharacteristics();
                        for (const ch of chars) {
                            if (ch.properties.write || ch.properties.writeWithoutResponse) {
                                foundChar = ch;
                                break;
                            }
                        }
                        if (foundChar) break;
                    } catch (e) { /* try next service */ }
                }

                if (!foundChar) throw new Error('Tidak ditemukan characteristic yang bisa ditulis pada printer Bluetooth');

                this.characteristic = foundChar;
                setStatus(true, 'Terhubung via BT: ' + (this.device.name || 'Thermal Printer'));
            }

            async send(data) {
                const CHUNK = 100;
                for (let i = 0; i < data.length; i += CHUNK) {
                    const chunk = data.slice(i, Math.min(i + CHUNK, data.length));
                    if (this.characteristic.properties.writeWithoutResponse) {
                        await this.characteristic.writeValueWithoutResponse(chunk);
                    } else {
                        await this.characteristic.writeValue(chunk);
                    }
                    await new Promise(r => setTimeout(r, 30));
                }
            }

            async disconnect() {
                try {
                    if (this.device && this.device.gatt.connected) {
                        this.device.gatt.disconnect();
                    }
                } catch (e) { /* ignore */ }
                this.device = null;
                setStatus(false, 'Terputus');
            }
        }

        // ===========================
        // Print to Thermal Printer
        // ===========================
        async function printViaThermal(type) {
            const btnId = type === 'usb' ? 'btn-usb' : 'btn-bluetooth';
            const btn = document.getElementById(btnId);

            // Check browser support
            if (type === 'usb' && !navigator.usb) {
                showToast('Browser tidak mendukung WebUSB. Gunakan Chrome/Edge.', 'error');
                return;
            }
            if (type === 'bluetooth' && !navigator.bluetooth) {
                showToast('Browser tidak mendukung Web Bluetooth. Gunakan Chrome/Edge.', 'error');
                return;
            }

            btn.classList.add('loading');
            btn.disabled = true;

            let printer = null;

            try {
                // Connect
                showToast('Menghubungkan ke printer...', 'info');
                printer = type === 'usb' ? new UsbPrinter() : new BluetoothPrinter();
                await printer.connect();

                // Build ESC/POS commands
                const paperSize = parseInt(document.getElementById('paper-size').value);
                const qrSize = parseInt(document.getElementById('qr-size').value);

                const builder = new EscPosBuilder();
                builder
                    .init()
                    .center()
                    .separator(paperSize)
                    .bold(true)
                    .text('INVENTARIS BARANG')
                    .newline()
                    .bold(false)
                    .separator(paperSize)
                    .newline()
                    .qrCode(ITEM_CODE, qrSize)
                    .newline()
                    .bold(true)
                    .textSize(1, 1)
                    .text(ITEM_CODE)
                    .newline()
                    .bold(false)
                    .newline()
                    .text(ITEM_NAME)
                    .newline();

                if (ITEM_LOCATION) {
                    builder.text(ITEM_LOCATION).newline();
                }

                builder
                    .separator(paperSize)
                    .feed(4)
                    .cut();

                // Send data
                showToast('Mengirim data ke printer...', 'info');
                await printer.send(builder.build());

                showToast('QR Code berhasil dicetak!', 'success');
            } catch (e) {
                if (e.name === 'NotFoundError') {
                    showToast('Tidak ada printer yang dipilih.', 'error');
                } else if (e.name === 'SecurityError') {
                    showToast('Akses ke perangkat ditolak. Pastikan menggunakan HTTPS atau localhost.', 'error');
                } else if (e.name === 'NetworkError') {
                    showToast('Koneksi ke printer gagal. Pastikan printer menyala.', 'error');
                } else {
                    showToast('Gagal mencetak: ' + e.message, 'error');
                    console.error('Thermal print error:', e);
                }
            } finally {
                btn.classList.remove('loading');
                btn.disabled = false;
                if (printer) {
                    setTimeout(() => printer.disconnect(), 2000);
                }
            }
        }
    </script>
</body>
</html>
