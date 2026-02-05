<x-layout>
    <x-slot name="title">Scan QR Code - Inventory System</x-slot>
    <x-slot name="header">Scan QR Code</x-slot>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Scan QR Code Barang</h2>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Scan Method Tabs -->
            <div class="flex mb-6 border-b">
                <button id="manualTab" class="flex-1 py-3 px-4 font-semibold border-b-2 border-blue-600 text-blue-600" onclick="switchTab('manual')">
                    <i class="fas fa-keyboard mr-2"></i>Input Manual
                </button>
                <button id="cameraTab" class="flex-1 py-3 px-4 font-semibold text-gray-600 hover:text-blue-600" onclick="switchTab('camera')">
                    <i class="fas fa-camera mr-2"></i>Scan Camera
                </button>
            </div>

            <!-- Manual Input Section -->
            <div id="manualSection">
                <form id="manualForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Kode QR Code</label>
                        <input type="text" id="manualCode" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kode QR code" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Lokasi Scan</label>
                        <input type="text" id="manualLocation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Ruang Guru, Lab Komputer">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                        <textarea id="manualNotes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-check mr-2"></i>Submit Scan
                    </button>
                </form>
            </div>

            <!-- Camera Section -->
            <div id="cameraSection" class="hidden">
                <div id="reader" class="mb-4"></div>
                <p class="text-sm text-gray-600 text-center">Arahkan kamera ke QR code untuk scan otomatis</p>
                
                <div id="cameraForm" class="mt-6 space-y-4 hidden">
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <p class="text-sm text-gray-700">Kode terdeteksi: <strong id="detectedCode"></strong></p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Lokasi Scan</label>
                        <input type="text" id="cameraLocation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Catatan (Opsional)</label>
                        <textarea id="cameraNotes" rows="3" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>

                    <button onclick="submitCameraScan()" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-check mr-2"></i>Submit Scan
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Scans -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Scan Terakhir Hari Ini</h3>
            <div id="recentScans">
                <p class="text-sm text-gray-500 text-center">Belum ada scan hari ini</p>
            </div>
        </div>
    </div>

    <script>
        let html5QrCode;
        let scannedCode = null;

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
                    html5QrCode.stop();
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

        function onScanSuccess(decodedText, decodedResult) {
            scannedCode = decodedText;
            document.getElementById('detectedCode').textContent = decodedText;
            document.getElementById('cameraForm').classList.remove('hidden');
            html5QrCode.stop();
        }

        function onScanFailure(error) {
            // Silent failure
        }

        // Manual Form Submit
        document.getElementById('manualForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const code = document.getElementById('manualCode').value;
            const location = document.getElementById('manualLocation').value;
            const notes = document.getElementById('manualNotes').value;

            await submitScan(code, 'manual', location, notes);
        });

        // Camera Form Submit
        async function submitCameraScan() {
            const location = document.getElementById('cameraLocation').value;
            const notes = document.getElementById('cameraNotes').value;

            await submitScan(scannedCode, 'camera', location, notes);
        }

        // Submit Scan Function
        async function submitScan(code, scanType, location, notes) {
            try {
                const response = await fetch('{{ route("staff.scan.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        code: code,
                        scan_type: scanType,
                        location: location,
                        notes: notes
                    })
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Scan Berhasil!',
                        text: data.item.name,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });
                    
                    // Reset forms
                    if (scanType === 'manual') {
                        document.getElementById('manualForm').reset();
                    } else {
                        document.getElementById('cameraForm').classList.add('hidden');
                        document.getElementById('cameraLocation').value = '';
                        document.getElementById('cameraNotes').value = '';
                        scannedCode = null;
                        startCamera();
                    }

                    addToRecentScans(data.item);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message,
                        confirmButtonColor: '#3b82f6'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: error.message,
                    confirmButtonColor: '#3b82f6'
                });
            }
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alertContainer');
            const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            alertContainer.innerHTML = `
                <div class="${bgColor} border px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">${message}</span>
                </div>
            `;

            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        function addToRecentScans(item) {
            const recentScans = document.getElementById('recentScans');
            const now = new Date().toLocaleString('id-ID');
            
            if (recentScans.querySelector('p')) {
                recentScans.innerHTML = '';
            }

            const scanDiv = document.createElement('div');
            scanDiv.className = 'border-b py-2';
            scanDiv.innerHTML = `
                <p class="font-semibold">${item.name}</p>
                <p class="text-sm text-gray-600">${item.code} - ${now}</p>
            `;
            
            recentScans.insertBefore(scanDiv, recentScans.firstChild);
        }
    </script>
</x-layout>