# REGLAB SYSTEM — PANDUAN INSTALASI (UBUNTU SERVER)

**Aplikasi:** Sistem Informasi Regulasi Laboratorium Teknik Kimia UAD
**Stack:** Laravel 12 · PHP 8.2+ · MySQL / MariaDB · Nginx · Vite + Tailwind CSS 4

---

## Daftar Isi

1. [Kebutuhan Sistem](#1-kebutuhan-sistem)
2. [Pemasangan Source Code](#2-pemasangan-source-code)
3. [Dependensi & Environment](#3-dependensi--environment)
4. [Storage Link & Direktori Upload](#4-storage-link--direktori-upload)
5. [Hak Akses Direktori](#5-hak-akses-direktori)
6. [Migrasi Database](#6-migrasi-database)
7. [Build Frontend](#7-build-frontend)
8. [Queue Worker (Supervisor)](#8-queue-worker-supervisor)
9. [Cron Job untuk Scheduler](#9-cron-job-untuk-scheduler)
10. [Konfigurasi Web Server (Nginx)](#10-konfigurasi-web-server-nginx)
11. [Optimasi Production](#11-optimasi-production)
12. [Verifikasi Instalasi](#12-verifikasi-instalasi)

---

## 1. Kebutuhan Sistem

### Paket OS & PHP

```bash
sudo apt update
sudo apt install -y php8.2-cli php8.2-fpm php8.2-mbstring php8.2-xml \
  php8.2-bcmath php8.2-curl php8.2-mysql php8.2-zip php8.2-gd php8.2-intl \
  php8.2-readline php8.2-tokenizer
```

> **Versi PHP minimal: 8.2** sesuai spesifikasi Laravel 12.

### Composer

```bash
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### Node.js & npm (untuk build frontend)

```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs
```

> **Node.js 18+** diperlukan untuk menjalankan Vite.

### MySQL / MariaDB

```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

Buat database dan user untuk aplikasi:

```sql
CREATE DATABASE reglab_tekkim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'reglab_user'@'127.0.0.1' IDENTIFIED BY 'password_aman';
GRANT ALL PRIVILEGES ON reglab_tekkim.* TO 'reglab_user'@'127.0.0.1';
FLUSH PRIVILEGES;
```

---

## 2. Pemasangan Source Code

Ekstrak atau clone berkas proyek ke direktori web server:

```bash
sudo mkdir -p /var/www/vhosts
cd /var/www/vhosts/

# Pilih salah satu:
# git clone <url-repo-git> Project_Tekkim
# ATAU ekstrak file zip yang diberikan

cd Project_Tekkim
```

> **PENTING:** Jangan upload folder `vendor/`, `node_modules/`, dan `public/build/` — folder ini akan di-generate ulang di server.

---

## 3. Dependensi & Environment

### Salin dan konfigurasi file `.env`

```bash
cp .env.example .env
```

Edit file `.env` menggunakan editor teks (`nano` / `vim`):

```env
APP_NAME="RegLab"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://reglab.tekkim.uad.ac.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reglab_tekkim
DB_USERNAME=reglab_user
DB_PASSWORD=password_aman

SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=smtp_user
MAIL_PASSWORD=smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@tekkim.uad.ac.id"
MAIL_FROM_NAME="${APP_NAME}"

```

> **Catatan:** Ganti nilai `MAIL_*` dan `RECAPTCHA_*` sesuai kredensial production yang digunakan.

### Install dependensi PHP

```bash
composer install --no-dev --optimize-autoloader
```

### Generate application key

```bash
php artisan key:generate
```

---

## 4. Storage Link & Direktori Upload

### Buat symbolic link untuk storage

```bash
php artisan storage:link
```

> Ini menghubungkan `storage/app/public` ke `public/storage` agar file upload dan dokumen yang di-generate bisa diakses via browser.

### Buat direktori upload yang diperlukan

```bash
mkdir -p public/uploads/profile
mkdir -p public/storage/alat-lab
mkdir -p public/temp-msds
mkdir -p storage/app/templates
mkdir -p storage/app/private
```

---

## 5. Hak Akses Direktori

```bash
# Ownership ke user web server
sudo chown -R www-data:www-data /var/www/vhosts/Project_Tekkim

# Izin baca/tulis/eksekusi untuk folder storage dan cache
chmod -R 775 storage bootstrap/cache

# Pastikan folder upload juga bisa ditulis
chmod -R 775 public/uploads
chmod -R 775 public/storage
```

---

## 6. Migrasi Database

```bash
php artisan migrate --force
```

> Flag `--force` wajib karena `APP_ENV=production`.

Jika ada data awal (seeder):

```bash
php artisan db:seed --force
```

---

## 7. Build Frontend

```bash
npm install
npm run build
```

> Ini akan meng-compile Tailwind CSS dan JavaScript ke `public/build/`. Tanpa langkah ini, **tampilan aplikasi akan rusak** (tanpa CSS/JS).

---

## 8. Queue Worker (Supervisor)

Aplikasi menggunakan **queue berbasis database** untuk pengiriman email (8 jenis email termasuk notifikasi deadline, approval, dll). Queue worker harus berjalan terus di background.

### Install Supervisor

```bash
sudo apt install -y supervisor
```

### Buat konfigurasi Supervisor

```bash
sudo nano /etc/supervisor/conf.d/reglab-worker.conf
```

Isi dengan:

```ini
[program:reglab-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/vhosts/Project_Tekkim/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/vhosts/Project_Tekkim/storage/logs/worker.log
stopwaitsecs=3600
```

### Jalankan Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start reglab-worker:*
```

> `numprocs=2` menjalankan 2 worker secara paralel. Sesuaikan dengan beban server.

---

## 9. Cron Job untuk Scheduler

Aplikasi memiliki **scheduled command** yang mengirim email reminder deadline **setiap hari jam 08:00**. Tambahkan cron entry berikut:

```bash
sudo crontab -u www-data -e
```

Tambahkan baris ini:

```
* * * * * cd /var/www/vhosts/Project_Tekkim && php artisan schedule:run >> /dev/null 2>&1
```

> Tanpa cron ini, email reminder deadline peminjaman alat **tidak akan terkirim**.

---

## 10. Konfigurasi Web Server (Nginx)

### Buat virtual host

```bash
sudo nano /etc/nginx/sites-available/reglab
```

Isi dengan:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name reglab.tekkim.uad.ac.id;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name reglab.tekkim.uad.ac.id;

    ssl_certificate     /etc/ssl/certs/reglab.crt;
    ssl_certificate_key /etc/ssl/private/reglab.key;

    root /var/www/vhosts/Project_Tekkim/public;
    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Max upload size (sesuaikan kebutuhan)
    client_max_body_size 10M;
}
```

> **Catatan:** Aplikasi secara otomatis memaksa HTTPS untuk environment production (bukan `local`).

### Aktifkan site dan restart Nginx

```bash
sudo ln -s /etc/nginx/sites-available/reglab /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 11. Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

> Setelah menjalankan perintah di atas, **jangan edit file `.env` tanpa menjalankan ulang** `php artisan config:cache`, karena konfigurasi sudah di-cache.

---

## 12. Verifikasi Instalasi

Setelah semua langkah selesai, verifikasi dengan checklist berikut:

| No | Item | Cara Cek |
|----|------|----------|
| 1 | Aplikasi bisa diakses via browser | Buka `https://reglab.tekkim.uad.ac.id` |
| 2 | CSS/JS termuat (tampilan normal) | Halaman login tampil rapi, bukan teks polos |
| 3 | Database terhubung | Bisa login dengan akun yang ada |
| 4 | File upload berfungsi | Upload foto profil berhasil |
| 5 | Email terkirim | Coba fitur yang mengirim email |
| 6 | Queue worker berjalan | `sudo supervisorctl status reglab-worker:*` → `RUNNING` |
| 7 | Cron scheduler aktif | `php artisan schedule:list` menampilkan `deadline:send-reminders` |
| 8 | Storage link benar | `ls -la public/storage` → menunjuk ke `../storage/app/public` |
| 9 | Log tidak ada error | `tail -f storage/logs/laravel.log` |

---

## Ringkasan Perintah Berurutan

```bash
# 1. Install paket sistem
sudo apt update
sudo apt install -y php8.2-cli php8.2-fpm php8.2-mbstring php8.2-xml \
  php8.2-bcmath php8.2-curl php8.2-mysql php8.2-zip php8.2-gd php8.2-intl \
  php8.2-readline php8.2-tokenizer mysql-server nodejs supervisor

# 2. Masuk ke direktori proyek
cd /var/www/vhosts/Project_Tekkim

# 3. Environment
cp .env.example .env
# Edit .env sesuai konfigurasi production

# 4. Install dependensi PHP
composer install --no-dev --optimize-autoloader

# 5. Generate key
php artisan key:generate

# 6. Storage link & direktori
php artisan storage:link
mkdir -p public/uploads/profile public/storage/alat-lab public/temp-msds

# 7. Hak akses
sudo chown -R www-data:www-data /var/www/vhosts/Project_Tekkim
chmod -R 775 storage bootstrap/cache public/uploads public/storage

# 8. Migrasi database
php artisan migrate --force

# 9. Build frontend
npm install
npm run build

# 10. Optimasi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 11. Setup Supervisor untuk queue worker
sudo supervisorctl reread
sudo supervisorctl update

# 12. Setup cron job
# Tambahkan ke crontab www-data:
# * * * * * cd /var/www/vhosts/Project_Tekkim && php artisan schedule:run >> /dev/null 2>&1
```

---

## Catatan Tambahan

- **Jangan pernah menjalankan `php artisan serve` di production.** Gunakan Nginx/Apache.
- Setelah mengubah file `.env`, selalu jalankan `php artisan config:cache` ulang.
- Jika ada update kode dari Git, jalankan ulang langkah 4, 8, 9, 10 dan restart queue worker:
  ```bash
  sudo supervisorctl restart reglab-worker:*
  ```
