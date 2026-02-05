<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ScanController as AdminScanController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboard;
use App\Http\Controllers\Guru\JurnalController;
use App\Http\Controllers\Guru\ReportController as GuruReport;
use App\Http\Controllers\Staff\ScanController as StaffScanController;
use App\Http\Controllers\Bendahara\DashboardController as BendaharaDashboard;
use App\Http\Controllers\Bendahara\AttendanceController as BendaharaAttendance;
use App\Http\Controllers\Bendahara\SlipGajiController;
use App\Http\Controllers\ChatController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Profile Routes
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [\App\Http\Controllers\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
    Route::put('/password', [\App\Http\Controllers\Auth\PasswordController::class, 'update'])->name('password.update');
    
    // Chatbot Route
    Route::post('/chat', [ChatController::class, 'chat'])->name('chat');
    
    // Admin Routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        
        // Guru Management
        Route::resource('guru', GuruController::class);
        Route::post('/guru/{guru}/reset-password', [GuruController::class, 'resetPassword'])->name('guru.reset-password');
        
        // Class Management
        Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class)->except(['show']);
        Route::post('/classes/{class}/toggle', [\App\Http\Controllers\Admin\ClassController::class, 'toggleStatus'])->name('classes.toggle');
        
        // Attendance Management
        Route::get('/attendance/daily', [\App\Http\Controllers\Admin\AttendanceController::class, 'daily'])->name('attendance.daily');
        Route::get('/attendance/monthly', [\App\Http\Controllers\Admin\AttendanceController::class, 'monthly'])->name('attendance.monthly');
        Route::get('/attendance/daily/pdf', [\App\Http\Controllers\Admin\AttendanceController::class, 'exportDailyPdf'])->name('attendance.daily.pdf');
        Route::get('/attendance/monthly/pdf', [\App\Http\Controllers\Admin\AttendanceController::class, 'exportMonthlyPdf'])->name('attendance.monthly.pdf');
        Route::get('/attendance/monthly/excel', [\App\Http\Controllers\Admin\AttendanceController::class, 'exportMonthlyExcel'])->name('attendance.monthly.excel');
        Route::get('/attendance/settings', [\App\Http\Controllers\Admin\AttendanceSettingController::class, 'index'])->name('attendance.settings');
        Route::put('/attendance/settings', [\App\Http\Controllers\Admin\AttendanceSettingController::class, 'update'])->name('attendance.settings.update');
        
        // Reports
        Route::get('/reports', [AdminReport::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [AdminReport::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/excel', [AdminReport::class, 'exportExcel'])->name('reports.excel');
        
        // Inventory Management
        Route::resource('items', ItemController::class);
        Route::get('/items/{item}/barcode', [ItemController::class, 'downloadBarcode'])->name('items.barcode');
        Route::get('/items/{item}/print', [ItemController::class, 'printBarcode'])->name('items.print');
        Route::resource('staff', StaffController::class);
        Route::get('/scans', [AdminScanController::class, 'index'])->name('scans.index');
        
        // Academic Year Management
        Route::resource('academic-years', \App\Http\Controllers\Admin\AcademicYearController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::put('/academic-years/{academicYear}/set-active', [\App\Http\Controllers\Admin\AcademicYearController::class, 'setActive'])->name('academic-years.set-active');
        
        // Password Reset Outbox
        Route::get('/outbox', [\App\Http\Controllers\Admin\OutboxController::class, 'index'])->name('outbox.index');
        Route::get('/outbox/{outbox}', [\App\Http\Controllers\Admin\OutboxController::class, 'show'])->name('outbox.show');
        Route::delete('/outbox/{outbox}', [\App\Http\Controllers\Admin\OutboxController::class, 'destroy'])->name('outbox.destroy');
        Route::post('/outbox/cleanup', [\App\Http\Controllers\Admin\OutboxController::class, 'cleanup'])->name('outbox.cleanup');
        Route::post('/outbox/mark-all-read', [\App\Http\Controllers\Admin\OutboxController::class, 'markAllRead'])->name('outbox.mark-all-read');
    });
    
    // Guru Routes
    Route::middleware('guru')->prefix('guru')->name('guru.')->group(function () {
        Route::get('/dashboard', [GuruDashboard::class, 'index'])->name('dashboard');
        
        // Jurnal Management
        Route::resource('jurnal', JurnalController::class);
        
        // Subject (Mata Pelajaran) Management
        Route::resource('subjects', \App\Http\Controllers\Guru\SubjectController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('/subjects/active', [\App\Http\Controllers\Guru\SubjectController::class, 'getActive'])->name('subjects.active');
        
        // Tujuan Pembelajaran Management
        Route::resource('tp', \App\Http\Controllers\Guru\TujuanPembelajaranController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::get('/tp/by-subject', [\App\Http\Controllers\Guru\TujuanPembelajaranController::class, 'getBySubject'])->name('tp.by-subject');
        
       // Attendance Routes
        Route::get('/attendance/checkin', function () {
            $settings = \App\Models\AttendanceSetting::current();
            $now = now();
            $workEndTime = \Carbon\Carbon::parse($settings->work_end);
            
            if ($now->format('H:i:s') > $workEndTime->format('H:i:s')) {
                return redirect()->route('guru.dashboard')
                    ->with('error', 'Maaf, waktu untuk absen berangkat telah berlalu. Jam pulang sudah dimulai pada ' . $settings->formatted_check_out_time . '.');
            }
            
            return view('guru.attendance.checkin-form');
        })->name('attendance.checkin.form');
        Route::get('/attendance/checkout', function () {
            return view('guru.attendance.checkout-form');
        })->name('attendance.checkout.form');
        Route::post('/attendance/checkin', [\App\Http\Controllers\Guru\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
        Route::post('/attendance/checkout', [\App\Http\Controllers\Guru\AttendanceController::class, 'checkOut'])->name('attendance.checkout');
        Route::get('/attendance/history', [\App\Http\Controllers\Guru\AttendanceController::class, 'history'])->name('attendance.history');
        
        // Reports
        Route::get('/reports', [GuruReport::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf', [GuruReport::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/excel', [GuruReport::class, 'exportExcel'])->name('reports.excel');
    });
    
    // Staff Routes
    Route::middleware('staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/scan', [StaffScanController::class, 'index'])->name('scan.index');
        Route::post('/scan', [StaffScanController::class, 'scan'])->name('scan.store');
        Route::get('/scan/history', [StaffScanController::class, 'history'])->name('scan.history');
    });

    // Bendahara Routes
    Route::middleware('bendahara')->prefix('bendahara')->name('bendahara.')->group(function () {
        Route::get('/dashboard', [BendaharaDashboard::class, 'index'])->name('dashboard');
        
        // Attendance Recap
        Route::get('/attendance/monthly', [BendaharaAttendance::class, 'monthly'])->name('attendance.monthly');
        Route::get('/attendance/monthly/pdf', [BendaharaAttendance::class, 'exportMonthlyPdf'])->name('attendance.monthly.pdf');
        
        // Slip Gaji Management
        Route::get('/slip-gaji', [SlipGajiController::class, 'index'])->name('slip-gaji.index');
        Route::get('/slip-gaji/create', [SlipGajiController::class, 'create'])->name('slip-gaji.create');
        Route::post('/slip-gaji', [SlipGajiController::class, 'store'])->name('slip-gaji.store');
        Route::post('/slip-gaji/generate-all', [SlipGajiController::class, 'generateAll'])->name('slip-gaji.generate-all');
        Route::get('/slip-gaji/{slipGaji}', [SlipGajiController::class, 'show'])->name('slip-gaji.show');
        Route::patch('/slip-gaji/{slipGaji}/status', [SlipGajiController::class, 'updateStatus'])->name('slip-gaji.update-status');
        Route::delete('/slip-gaji/{slipGaji}', [SlipGajiController::class, 'destroy'])->name('slip-gaji.destroy');
        Route::get('/slip-gaji/{slipGaji}/print', [SlipGajiController::class, 'print'])->name('slip-gaji.print');
    });
});