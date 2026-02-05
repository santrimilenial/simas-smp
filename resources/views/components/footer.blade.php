<footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 md:px-8 py-8 md:py-12">
        <!-- Footer Top -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- About Section -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-500 rounded-lg p-2 mr-3">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">SIMAS</h3>
                        <p class="text-sm text-gray-400">Sistem Informasi Manajemen Sekolah</p>
                    </div>
                </div>
                <p class="text-gray-400 text-sm mb-4">
                    Sistem informasi manajemen sekolah berbasis web yang memudahkan guru dalam mencatat dan mengelola kegiatan pembelajaran harian.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="bg-gray-700 hover:bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.tiktok.com/@khaydarfikri" class="bg-gray-700 hover:bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="https://www.instagram.com/khaydarfikrii_?igsh=cXN2ZTEyMGpsbTJw" class="bg-gray-700 hover:bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="bg-gray-700 hover:bg-blue-600 w-10 h-10 rounded-full flex items-center justify-center transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold mb-4">Menu Cepat</h4>
                <ul class="space-y-2">
                    @if(auth()->user()->isAdmin())
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.guru.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Kelola Guru
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.classes.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Kelola Kelas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Laporan
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('guru.dashboard') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru.jurnal.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Jurnal Mengajar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru.reports.index') }}" class="text-gray-400 hover:text-white transition flex items-center">
                                <i class="fas fa-chevron-right text-xs mr-2"></i> Laporan
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-lg font-bold mb-4">Kontak</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start text-gray-400">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-blue-500"></i>
                        <span>Jl. Banyumas KM.08<br>Selomerto, Wonosobo</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-phone mt-1 mr-3 text-blue-500"></i>
                        <span>082323888054</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-envelope mt-1 mr-3 text-blue-500"></i>
                        <span>milenialsantri@gmail.com</span>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-clock mt-1 mr-3 text-blue-500"></i>
                        <span>Senin - Sabtu: 07:00 - 12:00</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="border-t border-gray-700 pt-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-400 text-center md:text-left">
                    <p>&copy; {{ date('Y') }} <span class="text-white font-semibold">SIMAS</span>. All rights reserved.</p>
                    <p class="mt-1">Developed with <i class="fas fa-heart text-red-500 mx-1"></i> by Gendons Websites</p>
                </div>
                <div class="flex gap-4 text-sm">
                    <a href="#" class="text-gray-400 hover:text-white transition">Kebijakan Privasi</a>
                    <span class="text-gray-600">|</span>
                    <a href="#" class="text-gray-400 hover:text-white transition">Syarat & Ketentuan</a>
                    <span class="text-gray-600">|</span>
                    <a href="#" class="text-gray-400 hover:text-white transition">Bantuan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button 
        id="backToTop" 
        class="fixed bottom-8 right-8 bg-blue-600 hover:bg-blue-700 text-white w-12 h-12 rounded-full shadow-lg hidden items-center justify-center transition-all duration-300 z-40 hover:scale-110"
        onclick="scrollToTop()"
    >
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<script>
    // Back to Top functionality
    const backToTopBtn = document.getElementById('backToTop');
    
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.remove('hidden');
            backToTopBtn.classList.add('flex');
        } else {
            backToTopBtn.classList.add('hidden');
            backToTopBtn.classList.remove('flex');
        }
    });
    
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
</script>