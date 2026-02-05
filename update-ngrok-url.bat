@echo off
echo ========================================
echo Update Laravel .env dengan Ngrok URL
echo ========================================
echo.

set /p NGROK_URL="Masukkan Ngrok URL (https://xxxx.ngrok-free.app): "

if "%NGROK_URL%"=="" (
    echo Error: URL tidak boleh kosong!
    pause
    exit /b
)

echo.
echo Mengupdate .env dengan URL: %NGROK_URL%
echo.

cd /d "%~dp0"

REM Backup .env
copy .env .env.backup >nul 2>&1
echo [OK] Backup .env ke .env.backup

REM Update APP_URL di .env
powershell -Command "(Get-Content .env) -replace '^APP_URL=.*', 'APP_URL=%NGROK_URL%' | Set-Content .env"
echo [OK] Update APP_URL di .env

REM Clear cache
echo.
echo Clearing Laravel cache...
php artisan config:clear
php artisan cache:clear
php artisan view:clear

echo.
echo ========================================
echo SELESAI!
echo ========================================
echo Aplikasi sekarang bisa diakses di:
echo %NGROK_URL%
echo ========================================
echo.
pause
