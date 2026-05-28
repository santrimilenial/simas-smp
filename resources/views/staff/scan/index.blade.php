<x-layout>
    <x-slot name="title">Scan QR Code - Inventory System</x-slot>
    <x-slot name="header">Scan QR Code</x-slot>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <div class="max-w-4xl mx-auto">
        {{-- Step Indicator --}}
        <div class="flex items-center justify-center mb-6">
            <div class="flex items-center">
                <div id="step1-indicator" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white bg-blue-600 shadow-md">1</div>
                <span class="ml-2 text-sm font-semibold text-blue-600" id="step1-label">Scan QR</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-300 mx-3" id="step-line-1"></div>
            <div class="flex items-center">
                <div id="step2-indicator" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white bg-gray-300">2</div>
                <span class="ml-2 text-sm font-semibold text-gray-400" id="step2-label">Detail Barang</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-300 mx-3" id="step-line-2"></div>
            <div class="flex items-center">
                <div id="step3-indicator" class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white bg-gray-300">3</div>
                <span class="ml-2 text-sm font-semibold text-gray-400" id="step3-label">Lapor Kondisi</span>
            </div>
        </div>

        {{-- ========== STEP 1: Scan QR Code ========== --}}
        <div id="step1-panel" class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
                <i class="fas fa-qrcode text-blue-500 mr-2"></i>Scan QR Code Barang
            </h2>

            <div id="alertContainer"></div>

            {{-- Scan Method Tabs --}}
            <div class="flex mb-6 border-b">
                <button id="manualTab" class="flex-1 py-3 px-4 font-semibold border-b-2 border-blue-600 text-blue-600" onclick="switchTab('manual')">
                    <i class="fas fa-keyboard mr-2"></i>Input Manual
                </button>
                <button id="cameraTab" class="flex-1 py-3 px-4 font-semibold text-gray-600 hover:text-blue-600" onclick="switchTab('camera')">
                    <i class="fas fa-camera mr-2"></i>Scan Camera
                </button>
            </div>

            {{-- Manual Input Section --}}
            <div id="manualSection">
                <form id="manualForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Kode QR Code</label>
                        <input type="text" id="manualCode" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kode QR code (cth: ITM001)" required>
                    </div>
                    <button type="submit" id="lookupBtn" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-search mr-2"></i>Cari Barang
                    </button>
                </form>
            </div>

            {{-- Camera Section --}}
            <div id="cameraSection" class="hidden">
                <div id="reader" class="mb-4 rounded-lg overflow-hidden"></div>
                <p class="text-sm text-gray-600 text-center mt-2">
                    <i class="fas fa-info-circle mr-1"></i>Arahkan kamera ke QR code untuk scan otomatis
                </p>
            </div>
        </div>

        {{-- ========== STEP 2: Item Details ========== --}}
        <div id="step2-panel" class="hidden">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                {{-- Item Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold" id="item-name">-</h2>
                            <p class="text-blue-100 mt-1" id="item-code-header">-</p>
                        </div>
                        <div id="item-qr-container" class="bg-white rounded-lg p-2 hidden">
                            <img id="item-qr-img" src="" alt="QR" class="w-20 h-20 object-contain">
                        </div>
                    </div>
                </div>

                {{-- Item Details Grid --}}
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>Informasi Barang
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-category">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Lokasi</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-location">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kondisi Saat Ini</p>
                            <p class="mt-1"><span id="item-condition" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold">-</span></p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jumlah</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-quantity">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Harga</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-price">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Pembelian</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-purchase-date">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Scan</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-total-scans">-</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Terakhir Discan</p>
                            <p class="text-gray-800 font-medium mt-1" id="item-last-scanned">-</p>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div id="item-desc-section" class="mt-4 bg-gray-50 rounded-lg p-4 hidden">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Deskripsi</p>
                        <p class="text-gray-800 mt-1" id="item-description">-</p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3 mt-6">
                        <button onclick="goToStep(1)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-arrow-left mr-2"></i>Scan Ulang
                        </button>
                        <button onclick="goToStep(3)" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-clipboard-check mr-2"></i>Laporkan Kondisi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== STEP 3: Report Condition ========== --}}
        <div id="step3-panel" class="hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">
                    <i class="fas fa-clipboard-check text-green-500 mr-2"></i>Laporkan Kondisi Barang
                </h2>
                <p class="text-gray-500 text-center mb-6">
                    <span id="report-item-name" class="font-semibold text-gray-700">-</span>
                    <span class="text-gray-400 mx-1">&mdash;</span>
                    <span id="report-item-code" class="font-mono text-sm">-</span>
                </p>

                <form id="reportForm" class="space-y-5">
                    {{-- Condition Selection --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-3">Kondisi Barang <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="condition_report" value="baik" class="peer sr-only" required>
                                <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300">
                                    <i class="fas fa-check-circle text-3xl text-green-500 mb-2"></i>
                                    <p class="font-bold text-gray-800">Baik</p>
                                    <p class="text-xs text-gray-500 mt-1">Berfungsi normal</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="condition_report" value="rusak ringan" class="peer sr-only">
                                <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-yellow-500 peer-checked:bg-yellow-50 hover:border-yellow-300">
                                    <i class="fas fa-exclamation-triangle text-3xl text-yellow-500 mb-2"></i>
                                    <p class="font-bold text-gray-800">Rusak Ringan</p>
                                    <p class="text-xs text-gray-500 mt-1">Perlu perbaikan kecil</p>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" name="condition_report" value="rusak berat" class="peer sr-only">
                                <div class="border-2 border-gray-200 rounded-xl p-4 text-center transition-all peer-checked:border-red-500 peer-checked:bg-red-50 hover:border-red-300">
                                    <i class="fas fa-times-circle text-3xl text-red-500 mb-2"></i>
                                    <p class="font-bold text-gray-800">Rusak Berat</p>
                                    <p class="text-xs text-gray-500 mt-1">Tidak bisa digunakan</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Lokasi Scan</label>
                        <input type="text" id="reportLocation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Ruang Guru, Lab Komputer">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Catatan Tambahan</label>
                        <textarea id="reportNotes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Jelaskan kondisi barang secara detail (opsional)"></textarea>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex gap-3">
                        <button type="button" onclick="goToStep(2)" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                        <button type="submit" id="submitReportBtn" class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Recent Scans --}}
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-history text-blue-500 mr-2"></i>Scan Terakhir Hari Ini
            </h3>
            <div id="recentScans">
                <p class="text-sm text-gray-500 text-center py-4">Belum ada scan hari ini</p>
            </div>
        </div>
    </div>

    <script>
        let html5QrCode;
        let scannedCode = null;
        let currentScanType = 'manual';
        let currentItem = null;

        // ===========================
        // Step Navigation
        // ===========================
        function goToStep(step) {
            document.getElementById('step1-panel').classList.toggle('hidden', step !== 1);
            document.getElementById('step2-panel').classList.toggle('hidden', step !== 2);
            document.getElementById('step3-panel').classList.toggle('hidden', step !== 3);

            for (let i = 1; i <= 3; i++) {
                const ind = document.getElementById(`step${i}-indicator`);
                const lbl = document.getElementById(`step${i}-label`);
                if (i < step) {
                    ind.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-white bg-green-500 shadow-md';
                    ind.innerHTML = '<i class="fas fa-check text-sm"></i>';
                    lbl.className = 'ml-2 text-sm font-semibold text-green-600';
                } else if (i === step) {
                    ind.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-white bg-blue-600 shadow-md';
                    ind.textContent = i;
                    lbl.className = 'ml-2 text-sm font-semibold text-blue-600';
                } else {
                    ind.className = 'w-10 h-10 rounded-full flex items-center justify-center font-bold text-white bg-gray-300';
                    ind.textContent = i;
                    lbl.className = 'ml-2 text-sm font-semibold text-gray-400';
                }
            }
            document.getElementById('step-line-1').className = step > 1 ? 'w-12 h-0.5 bg-green-500 mx-3' : 'w-12 h-0.5 bg-gray-300 mx-3';
            document.getElementById('step-line-2').className = step > 2 ? 'w-12 h-0.5 bg-green-500 mx-3' : 'w-12 h-0.5 bg-gray-300 mx-3';

            if (step === 1) {
                currentItem = null;
                scannedCode = null;
                if (html5QrCode) {
                    try { html5QrCode.stop(); } catch(e) {}
                }
            }
        }

        // ===========================
        // Tab Switching
        // ===========================
        function switchTab(tab) {
            const manualTab = document.getElementById('manualTab');
            const cameraTab = document.getElementById('cameraTab');
            const manualSection = document.getElementById('manualSection');
            const cameraSection = document.getElementById('cameraSection');

            if (tab === 'manual') {
                manualTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
                manualTab.classList.remove('text-gray-600');
                cameraTab.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                cameraTab.classList.add('text-gray-600');
                manualSection.classList.remove('hidden');
                cameraSection.classList.add('hidden');
                if (html5QrCode) {
                    try { html5QrCode.stop(); } catch(e) {}
                }
            } else {
                cameraTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
                cameraTab.classList.remove('text-gray-600');
                manualTab.classList.remove('border-b-2', 'border-blue-600', 'text-blue-600');
                manualTab.classList.add('text-gray-600');
                manualSection.classList.add('hidden');
                cameraSection.classList.remove('hidden');
                startCamera();
            }
        }

        // ===========================
        // Camera
        // ===========================
        function startCamera() {
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengakses Kamera',
                    text: err.message || 'Periksa izin kamera browser Anda',
                    confirmButtonColor: '#3b82f6'
                });
            });
        }

        function onScanSuccess(decodedText) {
            scannedCode = decodedText;
            currentScanType = 'camera';
            html5QrCode.stop();
            lookupItem(decodedText);
        }

        function onScanFailure(error) {}

        // ===========================
        // Lookup Item (Step 1 → Step 2)
        // ===========================
        document.getElementById('manualForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const code = document.getElementById('manualCode').value.trim();
            if (!code) return;
            scannedCode = code;
            currentScanType = 'manual';
            await lookupItem(code);
        });

        async function lookupItem(code) {
            const btn = document.getElementById('lookupBtn');
            const origText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mencari...';
            btn.disabled = true;

            try {
                const response = await fetch('{{ route("staff.scan.lookup") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ code: code })
                });
                const data = await response.json();

                if (data.success) {
                    currentItem = data.item;
                    populateItemDetails(data.item);
                    goToStep(2);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak Ditemukan',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Gagal menghubungi server.',
                    confirmButtonColor: '#3b82f6'
                });
            } finally {
                btn.innerHTML = origText;
                btn.disabled = false;
            }
        }

        // ===========================
        // Populate Item Detail (Step 2)
        // ===========================
        function populateItemDetails(item) {
            document.getElementById('item-name').textContent = item.name;
            document.getElementById('item-code-header').textContent = item.code;
            document.getElementById('item-category').textContent = item.category || '-';
            document.getElementById('item-location').textContent = item.location || '-';
            document.getElementById('item-quantity').textContent = item.quantity ?? '-';
            document.getElementById('item-total-scans').textContent = item.total_scans ?? '0';
            document.getElementById('item-last-scanned').textContent = item.last_scanned || 'Belum pernah';
            document.getElementById('item-purchase-date').textContent = item.purchase_date || '-';
            document.getElementById('item-price').textContent = item.price ? 'Rp ' + Number(item.price).toLocaleString('id-ID') : '-';

            // Condition badge
            const condEl = document.getElementById('item-condition');
            const condMap = {
                'baik': { bg: 'bg-green-100 text-green-800', icon: 'fa-check-circle', label: 'Baik' },
                'rusak ringan': { bg: 'bg-yellow-100 text-yellow-800', icon: 'fa-exclamation-triangle', label: 'Rusak Ringan' },
                'rusak berat': { bg: 'bg-red-100 text-red-800', icon: 'fa-times-circle', label: 'Rusak Berat' },
            };
            const cond = condMap[item.condition] || condMap['baik'];
            condEl.className = `inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold ${cond.bg}`;
            condEl.innerHTML = `<i class="fas ${cond.icon}"></i> ${cond.label}`;

            // QR image
            if (item.barcode_path) {
                document.getElementById('item-qr-img').src = item.barcode_path;
                document.getElementById('item-qr-container').classList.remove('hidden');
            } else {
                document.getElementById('item-qr-container').classList.add('hidden');
            }

            // Description
            if (item.description) {
                document.getElementById('item-description').textContent = item.description;
                document.getElementById('item-desc-section').classList.remove('hidden');
            } else {
                document.getElementById('item-desc-section').classList.add('hidden');
            }

            // Pre-fill step 3
            document.getElementById('report-item-name').textContent = item.name;
            document.getElementById('report-item-code').textContent = item.code;

            // Pre-select current condition in radio
            const radios = document.querySelectorAll('input[name="condition_report"]');
            radios.forEach(r => { r.checked = r.value === item.condition; });
        }

        // ===========================
        // Submit Report (Step 3)
        // ===========================
        document.getElementById('reportForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const conditionReport = document.querySelector('input[name="condition_report"]:checked');
            if (!conditionReport) {
                Swal.fire({ icon: 'warning', title: 'Pilih Kondisi', text: 'Silakan pilih kondisi barang.', confirmButtonColor: '#3b82f6' });
                return;
            }

            const btn = document.getElementById('submitReportBtn');
            const origText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengirim...';
            btn.disabled = true;

            try {
                const response = await fetch('{{ route("staff.scan.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        code: scannedCode,
                        scan_type: currentScanType,
                        location: document.getElementById('reportLocation').value,
                        notes: document.getElementById('reportNotes').value,
                        condition_report: conditionReport.value,
                    })
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Laporan Berhasil!',
                        html: `<p class="mb-1"><strong>${data.item.name}</strong></p><p class="text-gray-500 text-sm">Kondisi: <strong>${conditionReport.value}</strong></p>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Scan Berikutnya',
                        confirmButtonColor: '#3b82f6'
                    }).then(() => {
                        // Reset everything
                        document.getElementById('manualForm').reset();
                        document.getElementById('reportForm').reset();
                        document.getElementById('reportLocation').value = '';
                        document.getElementById('reportNotes').value = '';
                        goToStep(1);
                    });

                    addToRecentScans(data.item, conditionReport.value);
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message, confirmButtonColor: '#3b82f6' });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan!', text: error.message, confirmButtonColor: '#3b82f6' });
            } finally {
                btn.innerHTML = origText;
                btn.disabled = false;
            }
        });

        // ===========================
        // Recent Scans List
        // ===========================
        function addToRecentScans(item, condition) {
            const recentScans = document.getElementById('recentScans');
            const now = new Date().toLocaleString('id-ID');

            if (recentScans.querySelector('p.text-gray-500')) {
                recentScans.innerHTML = '';
            }

            const condColors = {
                'baik': 'bg-green-100 text-green-800',
                'rusak ringan': 'bg-yellow-100 text-yellow-800',
                'rusak berat': 'bg-red-100 text-red-800',
            };

            const scanDiv = document.createElement('div');
            scanDiv.className = 'flex items-center justify-between border-b border-gray-100 py-3';
            scanDiv.innerHTML = `
                <div>
                    <p class="font-semibold text-gray-800">${item.name}</p>
                    <p class="text-sm text-gray-500">${item.code} &middot; ${now}</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold ${condColors[condition] || ''}">${condition}</span>
            `;

            recentScans.insertBefore(scanDiv, recentScans.firstChild);
        }
    </script>
</x-layout>