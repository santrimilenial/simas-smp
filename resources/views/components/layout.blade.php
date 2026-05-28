<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SIMAS - Sistem Informasi Manajemen Sekolah' }}</title>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#1e3a5f">
    <meta name="description" content="SIMAS - Sistem Informasi Manajemen Sekolah untuk pengelolaan jurnal mengajar">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="SIMAS">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .sidebar-transition {
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out;
        }
        .content-transition {
            transition: margin-left 0.3s ease-in-out;
        }
        .sidebar-text {
            transition: opacity 0.2s ease-in-out;
        }
        
        /* Desktop collapsed state */
        @media (min-width: 1024px) {
            .sidebar-collapsed .sidebar-text {
                opacity: 0;
                display: none;
            }
            .sidebar-collapsed {
                width: 5rem !important;
            }
            .sidebar-collapsed .flex.items-center.justify-between {
                justify-content: center !important;
            }
            .sidebar-collapsed button[onclick*="toggleSubmenu"] .chevron-icon,
            .sidebar-collapsed .submenu {
                display: none !important;
            }
            .sidebar-collapsed .flex.items-center {
                justify-content: center;
            }
            .sidebar-collapsed a, 
            .sidebar-collapsed button {
                justify-content: center !important;
                padding-left: 1.5rem !important;
                padding-right: 1.5rem !important;
            }
            .sidebar-collapsed .sidebar-text {
                display: none !important;
            }
            .sidebar-collapsed .sidebar-collapsed-icon {
                display: flex !important;
            }
        }
        
        .sidebar-collapsed-icon {
            display: none;
        }
        
        /* Mobile & Tablet state (hide sidebar by default) */
        @media (max-width: 1023px) {
            .sidebar-mobile-hidden {
                transform: translateX(-100%) !important;
            }
            .overlay {
                transition: opacity 0.3s ease-in-out;
            }
        }

        /* Submenu styles */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        .submenu.open {
            max-height: 300px;
        }
        .submenu-item {
            padding-left: 3.5rem;
        }
        .chevron-icon {
            transition: transform 0.3s ease;
        }
        .chevron-icon.rotate {
            transform: rotate(180deg);
        }

        /* SweetAlert2 Custom Styles */
        .swal-popup-custom {
            font-family: inherit;
        }
        .swal2-timer-progress-bar {
            background: rgba(59, 130, 246, 0.8);
        }
        .swal2-icon.swal2-success .swal2-success-ring {
            border-color: rgba(16, 185, 129, 0.3);
        }
        .swal2-icon.swal2-success [class^='swal2-success-line'] {
            background-color: #10b981;
        }
        .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
            background-color: #ef4444;
        }
        .swal-btn-confirm {
            font-weight: 600 !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
        }
        .swal-btn-cancel {
            font-weight: 600 !important;
            padding: 10px 24px !important;
            border-radius: 8px !important;
        }
        .swal2-html-container {
            margin: 1.5em 1em !important;
        }

        /* Custom scrollbar for sidebar */
        nav::-webkit-scrollbar {
            width: 6px;
        }
        nav::-webkit-scrollbar-track {
            background: transparent;
        }
        nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 3px;
        }
        nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.5);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Overlay for Mobile/Tablet -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden overlay" style="display: none;"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 lg:w-64 bg-gradient-to-b from-blue-600 to-blue-800 text-white fixed inset-y-0 left-0 z-50 sidebar-transition lg:z-30 sidebar-mobile-hidden lg:translate-x-0 flex flex-col">
            <div class="p-4 flex-shrink-0 border-b border-blue-500/30">
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center flex-1 sidebar-text min-w-0">
                        <!-- Logo Icon -->
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center mr-3 flex-shrink-0 shadow-lg">
                            <i class="fas fa-graduation-cap text-xl text-white"></i>
                        </div>
                        <!-- Text -->
                        <div class="flex flex-col min-w-0 flex-1">
                            <h2 class="text-lg font-bold tracking-wide">SIMAS</h2>
                            <p class="text-blue-200 text-[10px] leading-tight truncate">Sistem Informasi Manajemen Sekolah</p>
                            <span class="inline-flex items-center mt-1">
                                <span class="px-2 py-0.5 bg-blue-500/40 rounded text-[9px] font-medium text-blue-100">
                                    @if(auth()->user()->isAdmin())
                                        Admin Panel
                                    @elseif(auth()->user()->isStaff())
                                        Staff Inventory
                                    @elseif(auth()->user()->isBendahara())
                                        Bendahara Panel
                                    @else
                                        Dashboard Guru
                                    @endif
                                </span>
                            </span>
                        </div>
                    </div>
                    <!-- Collapsed state icon -->
                    <div class="hidden sidebar-collapsed-icon w-10 h-10 bg-white/20 rounded-xl items-center justify-center shadow-lg">
                        <i class="fas fa-graduation-cap text-xl text-white"></i>
                    </div>
                    <!-- Close button for mobile & tablet -->
                    <button id="closeSidebar" class="lg:hidden w-8 h-8 flex items-center justify-center bg-white/10 hover:bg-red-500 text-white rounded-lg flex-shrink-0 transition-colors">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            
            <nav class="flex-1 overflow-y-auto overflow-x-hidden mt-3 pb-4 px-0" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent; min-height: 0;">
                @if(auth()->user()->isAdmin())
                    {{-- ADMIN MENU --}}
                    
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Dashboard">
                        <i class="fas fa-home fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Dashboard</span>
                    </a>
                    
                    <!-- Kelola Guru -->
                    <a href="{{ route('admin.guru.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.guru.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Kelola Guru">
                        <i class="fas fa-users fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Kelola Guru</span>
                    </a>
                    
                    <!-- Informasi Guru -->
                    <a href="{{ route('admin.guru-info.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.guru-info.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Informasi Guru">
                        <i class="fas fa-id-card fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Informasi Guru</span>
                    </a>
                    
                    <!-- Kelola Staff -->
                    <a href="{{ route('admin.staff.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.staff.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Kelola Staff">
                        <i class="fas fa-user-tie fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Kelola Staff</span>
                    </a>
                    
                    <!-- Kelola Kelas -->
                    <a href="{{ route('admin.classes.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.classes.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Kelola Kelas">
                        <i class="fas fa-chalkboard fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Kelola Kelas</span>
                    </a>
                    
                    <!-- Tahun Ajaran -->
                    <a href="{{ route('admin.academic-years.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.academic-years.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Tahun Ajaran">
                        <i class="fas fa-calendar-alt fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Tahun Ajaran</span>
                    </a>
                    
                    <!-- Absensi with Submenu -->
                    <div>
                        <button onclick="toggleSubmenu('attendance')" class="w-full flex items-center justify-between px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.attendance.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Absensi">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check fa-fw text-lg w-6 text-center"></i>
                                <span class="ml-3 text-sm sidebar-text">Absensi</span>
                            </div>
                            <i class="fas fa-chevron-down chevron-icon text-xs sidebar-text" id="attendance-chevron"></i>
                        </button>
                        
                        <!-- Submenu -->
                        <div id="attendance-submenu" class="submenu {{ request()->routeIs('admin.attendance.*') ? 'open' : '' }}">
                            <a href="{{ route('admin.attendance.daily') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('admin.attendance.daily') ? 'bg-blue-700' : '' }}" title="Laporan Harian">
                                <i class="far fa-calendar-day fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Laporan Harian</span>
                            </a>
                            <a href="{{ route('admin.attendance.monthly') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('admin.attendance.monthly') ? 'bg-blue-700' : '' }}" title="Laporan Bulanan">
                                <i class="far fa-calendar-alt fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Laporan Bulanan</span>
                            </a>
                            <a href="{{ route('admin.attendance.settings') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('admin.attendance.settings') ? 'bg-blue-700' : '' }}" title="Pengaturan">
                                <i class="fas fa-cog fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Pengaturan</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Laporan -->
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.reports.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Laporan">
                        <i class="fas fa-file-alt fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Laporan</span>
                    </a>
                    
                    <!-- Outbox Password Reset -->
                    <a href="{{ route('admin.outbox.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.outbox.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Outbox">
                        <i class="fas fa-envelope fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Outbox</span>
                        @php
                            $unreadCount = \App\Models\PasswordResetLog::unread()->valid()->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full sidebar-text">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    
                    <!-- Inventory with Submenu -->
                    <div>
                        <button onclick="toggleSubmenu('inventory')" class="w-full flex items-center justify-between px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('admin.items.*') || request()->routeIs('admin.scans.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Inventory">
                            <div class="flex items-center">
                                <i class="fas fa-boxes fa-fw text-lg w-6 text-center"></i>
                                <span class="ml-3 text-sm sidebar-text">Inventory</span>
                            </div>
                            <i class="fas fa-chevron-down chevron-icon text-xs sidebar-text" id="inventory-chevron"></i>
                        </button>
                        
                        <!-- Submenu -->
                        <div id="inventory-submenu" class="submenu {{ request()->routeIs('admin.items.*') || request()->routeIs('admin.scans.*') ? 'open' : '' }}">
                            <a href="{{ route('admin.items.index') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('admin.items.index') || request()->routeIs('admin.items.show') || request()->routeIs('admin.items.create') || request()->routeIs('admin.items.edit') ? 'bg-blue-700' : '' }}" title="Daftar Barang">
                                <i class="fas fa-box fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Daftar Barang</span>
                            </a>
                            <a href="{{ route('admin.scans.index') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('admin.scans.*') ? 'bg-blue-700' : '' }}" title="Riwayat Scan">
                                <i class="fas fa-qrcode fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Riwayat Scan</span>
                            </a>
                            <a href="{{ route('admin.items.report') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('admin.items.report*') ? 'bg-blue-700' : '' }}" title="Laporan Barang">
                                <i class="fas fa-chart-pie fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Laporan Barang</span>
                            </a>
                        </div>
                    </div>
                    
                @elseif(auth()->user()->isGuru())
                    {{-- GURU MENU --}}
                    
                    <!-- Dashboard -->
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.dashboard') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Dashboard">
                        <i class="fas fa-home fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Dashboard</span>
                    </a>
                    
                    <!-- Mata Pelajaran -->
                    <a href="{{ route('guru.subjects.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.subjects.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Mata Pelajaran">
                        <i class="fas fa-book-open fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Mata Pelajaran</span>
                    </a>
                    
                    <!-- Tujuan Pembelajaran -->
                    <a href="{{ route('guru.tp.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.tp.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Tujuan Pembelajaran">
                        <i class="fas fa-list-check fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Tujuan Pembelajaran</span>
                    </a>
                    
                    <!-- Jurnal Mengajar -->
                    <a href="{{ route('guru.jurnal.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.jurnal.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Jurnal Mengajar">
                        <i class="fas fa-book fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Jurnal Mengajar</span>
                    </a>
                    
                    <!-- Absensi with Submenu -->
                    <div>
                        <button onclick="toggleSubmenu('guru-attendance')" class="w-full flex items-center justify-between px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.attendance.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Absensi">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-check fa-fw text-lg w-6 text-center"></i>
                                <span class="ml-3 text-sm sidebar-text">Absensi</span>
                            </div>
                            <i class="fas fa-chevron-down chevron-icon text-xs sidebar-text" id="guru-attendance-chevron"></i>
                        </button>
                        
                        <!-- Submenu -->
                        <div id="guru-attendance-submenu" class="submenu {{ request()->routeIs('guru.attendance.*') ? 'open' : '' }}">
                            <a href="{{ route('guru.attendance.checkin.form') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('guru.attendance.checkin*') ? 'bg-blue-700' : '' }}" title="Absen Berangkat">
                                <i class="fas fa-arrow-right-to-bracket fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Absen Berangkat</span>
                            </a>
                            <a href="{{ route('guru.attendance.checkout.form') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('guru.attendance.checkout*') ? 'bg-blue-700' : '' }}" title="Absen Pulang">
                                <i class="fas fa-arrow-right-from-bracket fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Absen Pulang</span>
                            </a>
                            <a href="{{ route('guru.attendance.history') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('guru.attendance.history') ? 'bg-blue-700' : '' }}" title="Riwayat Absensi">
                                <i class="fas fa-history fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Riwayat Absensi</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Laporan -->
                    <a href="{{ route('guru.reports.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.reports.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Laporan">
                        <i class="fas fa-file-alt fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Laporan</span>
                    </a>
                    
                    <!-- Informasi Guru -->
                    <a href="{{ route('guru.info') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('guru.info') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Informasi Saya">
                        <i class="fas fa-id-card fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Informasi Saya</span>
                    </a>
                @elseif(auth()->user()->isStaff())
                    {{-- STAFF MENU --}}
                    
                    <!-- Scan QR Code -->
                    <a href="{{ route('staff.scan.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('staff.scan.index') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Scan QR Code">
                        <i class="fas fa-qrcode fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Scan QR Code</span>
                    </a>
                    
                    <!-- Riwayat Scan -->
                    <a href="{{ route('staff.scan.history') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('staff.scan.history') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Riwayat Scan">
                        <i class="fas fa-history fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Riwayat Scan</span>
                    </a>
                @elseif(auth()->user()->isBendahara())
                    {{-- BENDAHARA MENU --}}
                    
                    <!-- Dashboard -->
                    <a href="{{ route('bendahara.dashboard') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('bendahara.dashboard') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Dashboard">
                        <i class="fas fa-home fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Dashboard</span>
                    </a>
                    
                    <!-- Rekap Absensi -->
                    <a href="{{ route('bendahara.attendance.monthly') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('bendahara.attendance.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Rekap Absensi">
                        <i class="fas fa-calendar-check fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Rekap Absensi</span>
                    </a>
                    
                    <!-- Slip Gaji with Submenu -->
                    <div>
                        <button onclick="toggleSubmenu('slip-gaji')" class="w-full flex items-center justify-between px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('bendahara.slip-gaji.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Slip Gaji">
                            <div class="flex items-center">
                                <i class="fas fa-file-invoice-dollar fa-fw text-lg w-6 text-center"></i>
                                <span class="ml-3 text-sm sidebar-text">Slip Gaji</span>
                            </div>
                            <i class="fas fa-chevron-down chevron-icon text-xs sidebar-text" id="slip-gaji-chevron"></i>
                        </button>
                        
                        <!-- Submenu -->
                        <div id="slip-gaji-submenu" class="submenu {{ request()->routeIs('bendahara.slip-gaji.*') ? 'open' : '' }}">
                            <a href="{{ route('bendahara.slip-gaji.index') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('bendahara.slip-gaji.index') ? 'bg-blue-700' : '' }}" title="Daftar Slip Gaji">
                                <i class="fas fa-list fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Daftar Slip Gaji</span>
                            </a>
                            <a href="{{ route('bendahara.slip-gaji.create') }}" class="flex items-center py-2 px-12 hover:bg-blue-700 transition {{ request()->routeIs('bendahara.slip-gaji.create') ? 'bg-blue-700' : '' }}" title="Buat Slip Gaji">
                                <i class="fas fa-plus-circle fa-fw text-sm w-5 text-center"></i>
                                <span class="ml-2 text-xs">Buat Slip Gaji</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Pencatatan Keuangan -->
                    <a href="{{ route('bendahara.keuangan.index') }}" class="flex items-center px-6 py-2.5 hover:bg-blue-700 transition {{ request()->routeIs('bendahara.keuangan.*') ? 'bg-blue-700 border-l-4 border-white' : '' }}" title="Pencatatan Keuangan">
                        <i class="fas fa-money-bill-wave fa-fw text-lg w-6 text-center"></i>
                        <span class="ml-3 text-sm sidebar-text">Pencatatan Keuangan</span>
                    </a>
                @endif
            </nav>

            <div class="flex-shrink-0 p-4 border-t border-blue-700 bg-blue-900 shadow-lg">
                <div class="flex items-center mb-3 sidebar-text">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" 
                         class="w-9 h-9 rounded-full object-cover flex-shrink-0 border-2 border-blue-400">
                    <div class="ml-3 overflow-hidden flex-1">
                        <p class="font-semibold text-sm truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-200">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                
                <!-- Profile & Settings Link -->
                <a href="{{ route('profile.edit') }}" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-2.5 px-4 rounded-lg text-sm flex items-center justify-center transition mb-2" title="Profil & Ubah Password">
                    <i class="fas fa-cog"></i>
                    <span class="ml-2 sidebar-text">Profil & Password</span>
                </a>
                
                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                    @csrf
                    <button type="button" onclick="confirmLogout()" class="w-full bg-red-500 hover:bg-red-600 text-white py-2.5 px-4 rounded-lg text-sm flex items-center justify-center transition" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="ml-2 sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div id="mainContent" class="lg:ml-64 content-transition">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm py-2 md:py-4 px-4 md:px-8 sticky top-0 z-30">
                <div class="flex justify-between items-center">
                    <div class="flex items-center flex-1">
                        <!-- Toggle Button -->
                        <button id="toggleSidebar" class="mr-3 md:mr-4 text-gray-600 hover:text-gray-800 focus:outline-none hover:bg-gray-100 p-2 rounded-lg transition">
                            <i class="fas fa-bars text-lg md:text-xl"></i>
                        </button>
                        <h1 class="text-lg md:text-2xl font-bold text-gray-800 truncate">{{ $header ?? 'Dashboard' }}</h1>
                    </div>
                    <div class="text-xs md:text-sm text-gray-600 hidden sm:block ml-4">
                        <i class="far fa-calendar mr-1 md:mr-2"></i>
                        <span class="hidden md:inline">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                        <span class="md:hidden">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <main class="p-4 md:p-8 min-h-screen">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <x-footer />
        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('overlay');
        
        let sidebarOpen = window.innerWidth >= 1024; // Default open on desktop

        function isMobile() {
            return window.innerWidth < 1024;
        }

        function toggleSidebar() {
            sidebarOpen = !sidebarOpen;
            
            if (isMobile()) {
                // Mobile/Tablet: overlay sidebar (< 1024px)
                if (sidebarOpen) {
                    sidebar.classList.remove('sidebar-mobile-hidden');
                    sidebar.style.transform = 'translateX(0)';
                    overlay.style.display = 'block';
                    setTimeout(() => overlay.classList.remove('hidden'), 10);
                    document.body.style.overflow = 'hidden'; // Prevent scroll
                } else {
                    sidebar.classList.add('sidebar-mobile-hidden');
                    sidebar.style.transform = 'translateX(-100%)';
                    overlay.classList.add('hidden');
                    setTimeout(() => overlay.style.display = 'none', 300);
                    document.body.style.overflow = ''; // Restore scroll
                }
            } else {
                // Desktop: collapse to icon-only (>= 1024px)
                if (sidebarOpen) {
                    sidebar.classList.remove('sidebar-collapsed');
                    sidebar.style.width = '16rem';
                    mainContent.style.marginLeft = '16rem';
                } else {
                    sidebar.classList.add('sidebar-collapsed');
                    sidebar.style.width = '5rem';
                    mainContent.style.marginLeft = '5rem';
                }
            }
        }

        // Toggle Submenu
        function toggleSubmenu(menuId) {
            const submenu = document.getElementById(menuId + '-submenu');
            const chevron = document.getElementById(menuId + '-chevron');
            
            if (submenu) {
                submenu.classList.toggle('open');
                if (chevron) {
                    chevron.classList.toggle('rotate');
                }
            }
        }

        // Initialize on load
        function initSidebar() {
            if (isMobile()) {
                // Mobile: hide by default
                sidebar.classList.add('sidebar-mobile-hidden');
                sidebar.style.transform = 'translateX(-100%)';
                mainContent.style.marginLeft = '0';
                overlay.style.display = 'none';
                sidebarOpen = false;
            } else {
                // Desktop: show by default
                sidebar.classList.remove('sidebar-mobile-hidden', 'sidebar-collapsed');
                sidebar.style.transform = 'translateX(0)';
                sidebar.style.width = '16rem';
                mainContent.style.marginLeft = '16rem';
                overlay.style.display = 'none';
                sidebarOpen = true;
            }
        }

        // Event listeners
        toggleBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                initSidebar();
            }, 250);
        });

        // Close sidebar on mobile when clicking menu item
        document.querySelectorAll('aside nav a').forEach(link => {
            link.addEventListener('click', () => {
                if (isMobile() && sidebarOpen) {
                    toggleSidebar();
                }
            });
        });

        // Initialize on page load
        initSidebar();

        // Initialize submenu state
        document.addEventListener('DOMContentLoaded', () => {
            // Auto-open submenu if on attendance route
            @if(request()->routeIs('admin.attendance.*'))
                const attendanceChevron = document.getElementById('attendance-chevron');
                if (attendanceChevron) {
                    attendanceChevron.classList.add('rotate');
                }
            @endif
        });

        // Notification System with SweetAlert2
        function showNotification(message, type = 'success', duration = 3000) {
            const config = {
                title: type === 'success' ? 'Berhasil!' : 
                       type === 'error' ? 'Error!' : 
                       type === 'warning' ? 'Peringatan!' : 'Informasi',
                text: message,
                icon: type,
                timer: duration,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center', // Centered
                customClass: {
                    popup: 'swal-popup-custom'
                }
            };
            
            Swal.fire(config);
        }

        // Show Laravel session notifications with SweetAlert2
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                showConfirmButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: "{{ session('warning') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center'
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: "{{ session('info') }}",
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'center'
            });
        @endif

        // Make showNotification globally accessible
        window.showNotification = showNotification;

        // Confirm Logout with SweetAlert2
        function confirmLogout() {
            Swal.fire({
                title: 'Logout?',
                text: "Apakah Anda yakin ingin keluar?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
        
        // Confirm Delete with SweetAlert2
        function confirmDelete(formId, itemName = 'data ini') {
            Swal.fire({
                title: 'Hapus Data?',
                html: `<p class="text-gray-700">Apakah Anda yakin ingin menghapus:</p><p class="font-bold text-lg mt-2 text-gray-900">${itemName}</p><p class="text-red-600 text-sm mt-3"><i class="fas fa-exclamation-triangle mr-1"></i>Data yang dihapus tidak dapat dikembalikan!</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fas fa-trash mr-2"></i>Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times mr-2"></i>Batal',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'swal-btn-confirm',
                    cancelButton: 'swal-btn-cancel'
                },
                buttonsStyling: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    document.getElementById(formId).submit();
                }
            });
        }
        
        // Make functions globally accessible
        window.confirmLogout = confirmLogout;
        window.confirmDelete = confirmDelete;
    </script>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
    
    <!-- Chatbot Widget -->
    <x-chatbot />
    
    @stack('scripts')
</body>
</html>
