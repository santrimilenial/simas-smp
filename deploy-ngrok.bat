@echo off
title Deploy Laravel dengan Ngrok
color 0A

echo.
echo ============================================
echo    DEPLOY LARAVEL DENGAN NGROK
echo ============================================
echo.

REM Cek apakah di folder yang benar
if not exist "artisan" (
    echo [ERROR] Script harus dijalankan di folder project Laravel!
    pause
    exit /b
)

echo [1] Starting Laravel Server...
start "Laravel Server" cmd /k "php artisan serve --host=0.0.0.0 --port=8000"
timeout /t 3 >nul

echo [2] Starting Ngrok...
start "Ngrok" cmd /k "C:\ngrok\ngrok.exe http 8000"
timeout /t 5 >nul

echo [3] Membuka Ngrok Dashboard...
start http://localhost:4040

echo.
echo ============================================
echo    STATUS
echo ============================================
echo [OK] Laravel Server running at http://localhost:8000
echo [OK] Ngrok running
echo [OK] Ngrok Dashboard: http://localhost:4040
echo.
echo LANGKAH SELANJUTNYA:
echo 1. Buka http://localhost:4040 di browser
echo 2. Copy URL HTTPS yang ditampilkan
echo 3. Jalankan: update-ngrok-url.bat
echo 4. Paste URL dan Enter
echo ============================================
echo.
pause
