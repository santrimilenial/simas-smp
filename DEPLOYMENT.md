# 🚀 Panduan Deployment Jurnal Mengajar ke Shared Hosting (Subdomain)

Dokumentasi lengkap untuk deploy aplikasi Jurnal Mengajar ke shared hosting menggunakan SSH pada subdomain (misal: `jurnal.yourdomain.com`).

---

## 📋 Daftar Isi

1. [Persyaratan](#persyaratan)
2. [Persiapan Lokal](#persiapan-lokal)
3. [Upload ke Server](#upload-ke-server)
4. [Konfigurasi Server](#konfigurasi-server)
5. [Setup Database](#setup-database)
6. [Konfigurasi Public Folder](#konfigurasi-public-folder)
    - **6.3. Setup Subdomain (RECOMMENDED)**
7. [Optimasi Production](#optimasi-production)
8. [Troubleshooting](#troubleshooting)
9. [Update Aplikasi](#update-aplikasi)

---

## 📌 Persyaratan

### Requirements Shared Hosting

- **PHP**: 8.1 atau lebih tinggi
- **MySQL**: 5.7 atau lebih tinggi
- **Extensions PHP**:
    - BCMath
    - Ctype
    - Fileinfo
    - JSON
    - Mbstring
    - OpenSSL
    - PDO
    - Tokenizer
    - XML
    - GD
    - ZIP
- **SSH Access**: Enabled
- **Composer**: Tersedia atau bisa di-upload manual
- **Disk Space**: Minimal 500MB
- **Memory Limit**: Minimal 128MB (disarankan 256MB)

### Tools yang Dibutuhkan di Lokal

- Git Bash / PowerShell / CMD
- SSH Client (OpenSSH, PuTTY, atau terminal bawaan)
- WinSCP / FileZilla (optional, untuk GUI)

### Informasi dari Hosting Provider

Siapkan informasi berikut sebelum deployment:

- [ ] **SSH Host**: `ssh.example.com` atau IP address
- [ ] **SSH Port**: Default `22` atau custom port
- [ ] **SSH Username**: Biasanya sama dengan cPanel username
- [ ] **SSH Password** atau **SSH Private Key**
- [ ] **Document Root Path**: `/home/username/jurnal-mengajar/public` (untuk subdomain)
- [ ] **Home Directory**: Biasanya `/home/username`
- [ ] **Database Host**: `localhost` atau IP database server
- [ ] **Database Name**: Nama database yang sudah dibuat
- [ ] **Database Username**: User database
- [ ] **Database Password**: Password database
- [ ] **Subdomain URL**: `https://jurnal.yourdomain.com` (contoh)

---

## 🔧 Persiapan Lokal

### 1. Bersihkan & Test Aplikasi

```powershell
# Pastikan di direktori project
cd C:\laragon\www\jurnal-mengajar

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Test migration (pastikan bersih)
php artisan migrate:fresh --seed

# Build assets untuk production
npm run build

# Test aplikasi berjalan normal
php artisan serve
```

### 2. Update Environment untuk Production

Buat file `.env.production` sebagai template:

```bash
cp .env .env.production
```

Edit `.env.production` dengan nilai production (akan dikonfigurasi di server nanti):

```env
APP_NAME="Jurnal Mengajar"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://jurnal.yourdomain.com  # URL subdomain Anda

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_db
DB_USERNAME=your_production_user
DB_PASSWORD=your_production_pass

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Admin credentials
ADMIN_NAME="Administrator"
ADMIN_EMAIL=admin@yourschool.com
ADMIN_PASSWORD=ChangeThisSecurePassword123!
ADMIN_NIY=1234567890
ADMIN_PHONE=08123456789
```

### 3. Buat Archive untuk Upload

**Opsi A: Menggunakan Git Archive (Recommended)**

```powershell
# Pastikan semua perubahan sudah di-commit
git add .
git commit -m "Prepare for production deployment"

# Buat archive tanpa .git, node_modules, dll
git archive --format=tar.gz -o jurnal-mengajar.tar.gz HEAD
```

**Opsi B: Manual Archive (Jika Tidak Pakai Git)**

```powershell
# Buat folder temporary
New-Item -ItemType Directory -Path "C:\temp\jurnal-deploy" -Force

# Copy files yang diperlukan (exclude yang tidak perlu)
# Bisa gunakan robocopy atau xcopy
robocopy . C:\temp\jurnal-deploy /E /XD node_modules vendor .git storage\logs storage\framework\cache storage\framework\sessions storage\framework\views .idea .vscode

# Compress
Compress-Archive -Path C:\temp\jurnal-deploy\* -DestinationPath C:\jurnal-mengajar.zip
```

**File/Folder yang TIDAK perlu di-upload:**

```
node_modules/
vendor/
.git/
.env (akan dibuat di server)
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
.idea/
.vscode/
tests/
*.log
.DS_Store
Thumbs.db
```

---

## 📤 Upload ke Server

### Metode 1: SCP (Secure Copy) - Recommended

```powershell
# Upload archive
scp jurnal-mengajar.tar.gz username@ssh.example.com:/home/username/

# Atau dengan port custom
scp -P 2222 jurnal-mengajar.tar.gz username@ssh.example.com:/home/username/
```

### Metode 2: RSYNC (Lebih Efisien untuk Update)

```powershell
# Upload langsung dengan exclude
rsync -avz --progress --exclude='node_modules' --exclude='vendor' --exclude='.git' --exclude='storage/logs/*' --exclude='.env' -e "ssh -p 22" ./ username@ssh.example.com:/home/username/jurnal-mengajar/
```

### Metode 3: FileZilla/WinSCP (GUI)

1. Buka FileZilla/WinSCP
2. Masukkan credentials:
    - Host: `ssh.example.com`
    - Port: `22`
    - Protocol: SFTP
    - Username: Your SSH username
    - Password: Your SSH password
3. Upload file `jurnal-mengajar.tar.gz` atau folder project

---

## ⚙️ Konfigurasi Server

### 1. Login ke SSH

```powershell
# Login SSH
ssh username@ssh.example.com

# Atau dengan port custom
ssh -p 2222 username@ssh.example.com
```

### 2. Setup Struktur Folder

```bash
# Pindah ke home directory
cd ~

# Buat folder aplikasi (di luar public_html untuk keamanan)
mkdir -p jurnal-mengajar

# Extract archive (jika upload tar.gz)
tar -xzf jurnal-mengajar.tar.gz -C jurnal-mengajar/

# Atau unzip (jika upload .zip)
unzip jurnal-mengajar.zip -d jurnal-mengajar/

# Pindah ke folder aplikasi
cd jurnal-mengajar

# Lihat isi folder
ls -la
```

**Struktur folder yang akan dibuat:**

```
/home/username/
├── jurnal-mengajar/              # Aplikasi Laravel (AMAN, di luar web root)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── public/                    # Folder yang akan di-symlink/copy
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── .env                       # File konfigurasi (akan dibuat)
│   ├── artisan
│   ├── composer.json
│   └── ...
│
└── public_html/                   # Web root (accessible via browser)
    ├── index.php                  # Modified untuk point ke ../jurnal-mengajar
    ├── .htaccess
    ├── build/                     # Vite assets
    ├── icons/
    └── ...
```

### 3. Install Composer Dependencies

**Opsi A: Jika Composer Sudah Tersedia**

```bash
# Install dependencies production
composer install --optimize-autoloader --no-dev

# Verify
composer --version
```

**Opsi B: Jika Composer Tidak Tersedia**

```bash
# Download Composer
cd ~/jurnal-mengajar
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"

# Install dependencies
php composer.phar install --optimize-autoloader --no-dev

# Buat alias (optional)
alias composer='php ~/jurnal-mengajar/composer.phar'
```

### 4. Setup Environment File

```bash
# Copy template
cp .env.example .env

# Edit dengan nano atau vim
nano .env
```

**Konfigurasi .env untuk Production:**

```env
APP_NAME="Jurnal Mengajar"
APP_ENV=production
APP_KEY=                              # Akan di-generate nanti
APP_DEBUG=false                        # PENTING: HARUS false di production
APP_URL=https://yourdomain.com        # Ganti dengan domain Anda

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error                       # Hanya log error di production

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost                     # Atau IP database server dari hosting
DB_PORT=3306
DB_DATABASE=your_database_name        # Nama database dari cPanel
DB_USERNAME=your_db_username          # Username database
DB_PASSWORD=your_db_password          # Password database

# Cache & Session
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail Configuration (jika pakai email)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Admin Default Credentials
ADMIN_NAME="Administrator"
ADMIN_EMAIL=admin@yourschool.com
ADMIN_PASSWORD=YourVerySecurePassword123!
ADMIN_NIY=1234567890
ADMIN_PHONE=08123456789
```

**Cara Edit dengan Nano:**

1. Tekan `Ctrl + O` untuk save
2. Tekan `Enter` untuk confirm
3. Tekan `Ctrl + X` untuk exit

### 5. Generate Application Key

```bash
# Generate key
php artisan key:generate

# Verify .env updated
cat .env | grep APP_KEY
```

### 6. Set Permissions

```bash
# Set permission untuk storage dan cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Pastikan owner benar (ganti 'username' dengan username Anda)
chown -R username:username storage
chown -R username:username bootstrap/cache

# Verify
ls -la storage
```

---

## 🗄️ Setup Database

### 1. Buat Database di cPanel

1. Login ke **cPanel**
2. Buka **MySQL Databases**
3. Buat database baru: `username_jurnal` (contoh)
4. Buat user baru dengan password yang kuat
5. Assign user ke database dengan **ALL PRIVILEGES**
6. Catat credentials:
    - DB Host: `localhost` (atau yang diberikan hosting)
    - DB Name: `username_jurnal`
    - DB User: `username_dbuser`
    - DB Password: `your_password`

### 2. Import Database (Jika Ada Data Existing)

**Opsi A: Via cPanel phpMyAdmin**

1. Buka phpMyAdmin di cPanel
2. Pilih database yang sudah dibuat
3. Klik tab **Import**
4. Upload file `.sql` dari backup
5. Klik **Go**

**Opsi B: Via SSH (Lebih Cepat)**

```bash
# Upload file .sql ke server dulu (via SCP)
scp database_backup.sql username@ssh.example.com:/home/username/

# Import via SSH
mysql -u your_db_user -p your_database_name < database_backup.sql
# Masukkan password database saat diminta
```

### 3. Run Migrations & Seeders

```bash
cd ~/jurnal-mengajar

# Test koneksi database
php artisan migrate:status

# Run migrations
php artisan migrate --force

# Seed data (admin user, dll)
php artisan db:seed --class=UserSeeder --force

# Atau seed semua
# php artisan db:seed --force
```

**PENTING:** Flag `--force` diperlukan karena APP_ENV=production

### 4. Create Storage Link

```bash
# Buat symbolic link dari storage ke public
php artisan storage:link

# Verify
ls -la public/storage
```

---

## 🌐 Konfigurasi Public Folder

Ada 2 metode: Symlink (lebih mudah) atau Copy & Modify (lebih compatible).

### Metode 1: Symlink (Recommended, Jika Hosting Support)

```bash
# Backup public_html existing (jika ada)
mv ~/public_html ~/public_html.backup

# Buat symlink dari public Laravel ke public_html
ln -s ~/jurnal-mengajar/public ~/public_html

# Verify
ls -la ~/public_html
```

**Kelebihan:**

- Mudah update, tinggal git pull di folder jurnal-mengajar
- Tidak perlu sync manual

**Kekurangan:**

- Tidak semua shared hosting support symlink

### Metode 2: Copy & Modify Paths (Lebih Universal)

#### A. Copy Files

```bash
# Copy semua isi folder public ke public_html
cp -r ~/jurnal-mengajar/public/* ~/public_html/

# Copy hidden files (.htaccess)
cp ~/jurnal-mengajar/public/.htaccess ~/public_html/

# Verify
ls -la ~/public_html
```

#### B. Modify index.php

```bash
# Edit index.php di public_html
nano ~/public_html/index.php
```

**Ubah dari:**

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
```

**Menjadi:**

```php
<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../jurnal-mengajar/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../jurnal-mengajar/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../jurnal-mengajar/bootstrap/app.php')
    ->handleRequest(Request::capture());
```

**Yang diubah:**

- `__DIR__.'/../'` → `__DIR__.'/../jurnal-mengajar/'`

Save dengan `Ctrl+O`, `Enter`, `Ctrl+X`

#### C. Verify .htaccess

Pastikan file `.htaccess` ada di `public_html`:

```bash
cat ~/public_html/.htaccess
```

Isinya harus seperti ini:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Metode 3: Setup Subdomain (Recommended untuk Deploy di Subdomain)

**Untuk deploy di subdomain seperti `jurnal.yourdomain.com`:**

#### A. Buat Subdomain di cPanel

1. **Login ke cPanel**
2. **Buka "Subdomains"** (di bagian Domains)
3. **Buat subdomain baru:**
    - **Subdomain**: `jurnal` (atau nama yang Anda inginkan)
    - **Domain**: Pilih domain utama Anda (misal: `yourdomain.com`)
    - **Document Root**: Ubah ke `/home/username/jurnal-mengajar/public`

        > **PENTING**: Jangan gunakan path default! Ubah ke folder `public` dari aplikasi Laravel
4. **Klik "Create"**

**Struktur folder setelah setup:**

```
/home/username/
├── jurnal-mengajar/              # Aplikasi Laravel
│   ├── app/
│   ├── public/                   # ← Document root subdomain point kesini
│   ├── storage/
│   └── ...
│
├── public_html/                  # Domain utama (tidak terpakai untuk jurnal)
│   └── ...
│
└── public_html/jurnal/           # Folder auto-created (TIDAK DIGUNAKAN, bisa dihapus)
```

#### B. Hapus Folder Auto-Generated (Optional)

cPanel biasanya otomatis membuat folder `public_html/jurnal`. Karena sudah set custom document root, folder ini tidak digunakan:

```bash
# SSH ke server
ssh username@ssh.example.com

# Hapus folder auto-generated (optional)
rm -rf ~/public_html/jurnal
```

#### C. Verifikasi Document Root

```bash
# Check symlink atau folder structure
ls -la ~/jurnal-mengajar/public

# Test akses
curl -I https://jurnal.yourdomain.com
```

#### D. Update .env untuk Subdomain

```bash
cd ~/jurnal-mengajar
nano .env
```

**Update APP_URL:**

```env
APP_URL=https://jurnal.yourdomain.com
```

**Update Session Domain (jika perlu):**

```env
SESSION_DOMAIN=.yourdomain.com
```

#### E. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

#### F. Test Subdomain

Buka browser ke: **https://jurnal.yourdomain.com**

**Kelebihan metode subdomain:**

- ✅ Paling mudah dan aman
- ✅ Tidak perlu symlink atau copy file
- ✅ Tidak mengganggu domain utama
- ✅ Mudah maintenance & update
- ✅ Lebih secure (Laravel files di luar web root)

**Catatan:**

- Pastikan SSL certificate sudah cover subdomain (Wildcard SSL atau add subdomain to SSL)
- Jika SSL belum ada, contact hosting untuk enable SSL di subdomain
- DNS propagation bisa memakan waktu 1-24 jam

---

## ⚡ Optimasi Production

### 1. Cache Configuration

```bash
cd ~/jurnal-mengajar

# Clear existing cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize --classmap-authoritative
```

### 2. Build Assets (Jika Belum di Lokal)

**Jika hosting punya Node.js & npm:**

```bash
# Check node version
node --version
npm --version

# Install dependencies
npm install

# Build production assets
npm run build
```

**Jika hosting TIDAK punya Node.js:**

Build di lokal, lalu upload folder `public/build/`:

```powershell
# Di lokal (Windows)
cd C:\laragon\www\jurnal-mengajar
npm run build

# Upload ke server
scp -r public/build username@ssh.example.com:/home/username/jurnal-mengajar/public/
```

### 3. Setup Cron Jobs untuk Queue & Scheduler (Optional)

Jika aplikasi menggunakan Queue atau Scheduler, setup di cPanel Cron Jobs:

**Di cPanel → Cron Jobs:**

**Untuk Laravel Scheduler (Recommended):**

```
* * * * * cd /home/username/jurnal-mengajar && php artisan schedule:run >> /dev/null 2>&1
```

**Untuk Queue Worker:**

```
* * * * * cd /home/username/jurnal-mengajar && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

### 4. Security Headers (Optional)

Edit `.htaccess` di `public_html`, tambahkan di bagian atas:

```apache
# Security Headers
<IfModule mod_headers.c>
    Header set X-XSS-Protection "1; mode=block"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Disable Directory Listing
Options -Indexes

# Existing Laravel .htaccess...
```

---

## ✅ Testing & Verification

### 1. Test Akses Website

```bash
# Test dari server
curl -I https://yourdomain.com

# Atau dari browser, buka:
# https://yourdomain.com
```

### 2. Test Login

1. Buka `https://yourdomain.com/login`
2. Login dengan credentials dari `.env`:
    - Email: `admin@yourschool.com`
    - Password: Password yang Anda set di `ADMIN_EMAIL`

### 3. Check Logs Jika Ada Error

```bash
# Lihat error log Laravel
tail -50 ~/jurnal-mengajar/storage/logs/laravel.log

# Lihat error log Apache/Server (lokasi bervariasi)
tail -50 ~/logs/error_log
# atau
tail -50 /var/log/apache2/error.log
```

### 4. Test Fitur Utama

- [ ] Login/Logout
- [ ] CRUD Jurnal Mengajar
- [ ] Upload/Download file (jika ada)
- [ ] Attendance/Presensi
- [ ] Export Excel/PDF
- [ ] Role & Permission (Admin vs Guru)

---

## 🔧 Troubleshooting

### Error 500 - Internal Server Error

**Penyebab umum:**

1. **Permission salah**

    ```bash
    chmod -R 775 storage bootstrap/cache
    chown -R username:username storage bootstrap/cache
    ```

2. **APP_KEY tidak di-set**

    ```bash
    php artisan key:generate
    ```

3. **Composer dependencies belum di-install**

    ```bash
    composer install --no-dev --optimize-autoloader
    ```

4. **File .env tidak ada atau salah**
    ```bash
    cp .env.example .env
    nano .env
    # Set all required values
    ```

**Check error detail:**

```bash
# Temporarily enable debug
nano .env
# Set: APP_DEBUG=true

# Access website and see error
# JANGAN LUPA set kembali ke false setelah debug!
```

### Error: SQLSTATE[HY000] [1045] Access denied

**Database credentials salah**

```bash
# Test koneksi database
mysql -h localhost -u your_db_user -p
# Enter password

# Jika berhasil, berarti credentials benar
# Update .env dengan credentials yang benar
```

### Error: Class not found / Autoload issues

```bash
# Regenerate autoload files
composer dump-autoload --optimize

# Clear cache
php artisan cache:clear
php artisan config:clear
```

### Assets tidak load (CSS/JS 404)

1. **Check APP_URL di .env**

    ```env
    APP_URL=https://yourdomain.com
    ```

2. **Pastikan folder build ada**

    ```bash
    ls -la ~/jurnal-mengajar/public/build
    # atau
    ls -la ~/public_html/build
    ```

3. **Run build lagi**

    ```bash
    npm run build
    # atau upload dari lokal
    ```

4. **Clear cache**
    ```bash
    php artisan config:cache
    ```

### Storage symlink tidak bekerja

```bash
# Hapus symlink lama
rm ~/jurnal-mengajar/public/storage

# Buat ulang
php artisan storage:link

# Jika masih gagal, copy manual
cp -r ~/jurnal-mengajar/storage/app/public/* ~/jurnal-mengajar/public/storage/
```

### Session tidak persist (selalu logout)

**Check session configuration:**

```bash
nano ~/jurnal-mengajar/.env
```

```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_DOMAIN=yourdomain.com  # Tambahkan ini
SESSION_SECURE_COOKIE=true     # Jika pakai HTTPS
```

**Clear session:**

```bash
php artisan session:table  # Jika pakai database
php artisan migrate
# atau
rm -rf ~/jurnal-mengajar/storage/framework/sessions/*
```

### HTTPS redirect loop

**Check .htaccess di public_html:**

Tambahkan sebelum Laravel rules:

```apache
# Force HTTPS
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

### Permission denied saat upload file

```bash
# Set permission untuk storage
chmod -R 775 storage/app
chown -R username:username storage/app

# Check disk space
df -h
```

---

## 🔄 Update Aplikasi

### Update via Git (Recommended)

```bash
# SSH ke server
ssh username@ssh.example.com
cd ~/jurnal-mengajar

# Backup sebelum update
php artisan down
cp .env .env.backup
mysqldump -u dbuser -p dbname > ~/backup_$(date +%Y%m%d).sql

# Pull update dari Git
git fetch origin
git pull origin main  # atau branch yang sesuai

# Update dependencies
composer install --no-dev --optimize-autoloader

# Run migrations (jika ada)
php artisan migrate --force

# Clear & rebuild cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart (up)
php artisan up
```

### Update Manual (Upload File)

```bash
# 1. Backup di server
ssh username@ssh.example.com
cd ~
tar -czf jurnal-backup-$(date +%Y%m%d).tar.gz jurnal-mengajar/
mysqldump -u dbuser -p dbname > backup_$(date +%Y%m%d).sql

# 2. Upload file baru dari lokal
# (via SCP atau FTP)

# 3. Extract & replace
cd ~/jurnal-mengajar
# ... extract files baru

# 4. Update dependencies & cache
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Rollback Jika Ada Masalah

```bash
# Restore dari backup
cd ~
tar -xzf jurnal-backup-20260204.tar.gz
mysql -u dbuser -p dbname < backup_20260204.sql

# Clear cache
cd jurnal-mengajar
php artisan config:clear
php artisan cache:clear
php artisan up
```

---

## 🔐 Security Checklist

Sebelum go-live, pastikan:

- [ ] `APP_DEBUG=false` di `.env`
- [ ] `APP_ENV=production` di `.env`
- [ ] Password database kuat & unik
- [ ] Admin password kuat (bukan "password")
- [ ] File `.env` tidak bisa diakses dari browser
- [ ] Folder `storage` dan `vendor` tidak bisa diakses dari browser
- [ ] HTTPS enabled (SSL certificate installed)
- [ ] Database user hanya punya akses ke database aplikasi
- [ ] Backup database rutin (daily/weekly)
- [ ] Error logs tidak expose sensitive info
- [ ] File upload validation aktif
- [ ] CSRF protection enabled (default di Laravel)
- [ ] XSS protection enabled
- [ ] SQL injection protection (gunakan Eloquent/Query Builder)

---

## 📱 Maintenance Mode

```bash
# Aktifkan maintenance mode
php artisan down

# Dengan pesan custom
php artisan down --message="Sedang maintenance, kembali 10 menit lagi"

# Dengan allowed IPs
php artisan down --allow=103.xxx.xxx.xxx

# Nonaktifkan
php artisan up
```

---

## 📊 Monitoring & Logs

### Check Application Logs

```bash
# Real-time log
tail -f ~/jurnal-mengajar/storage/logs/laravel.log

# Last 50 lines
tail -50 ~/jurnal-mengajar/storage/logs/laravel.log

# Search for error
grep -i "error" ~/jurnal-mengajar/storage/logs/laravel.log
```

### Check Server Logs

```bash
# Apache error log (lokasi bervariasi)
tail -50 ~/logs/error_log

# Check disk usage
df -h

# Check memory
free -m

# Check running processes
ps aux | grep php
```

---

## 🆘 Support & Dokumentasi

### Laravel Official

- **Documentation**: https://laravel.com/docs
- **Deployment**: https://laravel.com/docs/deployment

### Aplikasi Jurnal Mengajar

- **Repository**: [URL repository Git Anda]
- **Issue Tracker**: [URL issue tracker]

### Hosting Support

- **cPanel Documentation**: Contact your hosting provider
- **SSH Issues**: Contact hosting technical support

---

## 📝 Changelog Deployment

| Date       | Version | Changes            | By    |
| ---------- | ------- | ------------------ | ----- |
| 2026-02-04 | 1.0.0   | Initial deployment | Admin |
|            |         |                    |       |

---

## ✅ Post-Deployment Checklist

Setelah deployment, verify semua fitur:

**Functionality:**

- [ ] Homepage loads correctly
- [ ] Login/Logout works
- [ ] Dashboard accessible
- [ ] CRUD operations work
- [ ] File upload/download works
- [ ] Database operations work
- [ ] Email sending works (if configured)
- [ ] PDF/Excel export works
- [ ] QR Code generation works
- [ ] Attendance system works

**Performance:**

- [ ] Page load time < 3 seconds
- [ ] Images optimized
- [ ] CSS/JS minified & cached
- [ ] Database queries optimized

**Security:**

- [ ] HTTPS working
- [ ] Debug mode OFF
- [ ] Error messages don't expose sensitive data
- [ ] File permissions correct
- [ ] .env file protected

**SEO (if needed):**

- [ ] robots.txt configured
- [ ] Sitemap generated
- [ ] Meta tags present

---

**🎉 Deployment Complete!**

Jika mengikuti semua langkah dengan benar, aplikasi Jurnal Mengajar seharusnya sudah berjalan di production.

Untuk pertanyaan atau masalah, check troubleshooting section atau contact support.

**Good luck! 🚀**
