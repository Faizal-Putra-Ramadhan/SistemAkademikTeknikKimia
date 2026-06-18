# REGLAB SYSTEM — PANDUAN INSTALASI

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
8. [Optimasi Production](#11-optimasi-production)

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


## 3. Dependensi & Environment

### Salin dan konfigurasi file `.env`

```bash
cp .env.example .env
```

Edit file `.env` 

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

### Data Awal (Seeder)

Setelah migrasi, jalankan seeder untuk mengisi data awal sistem:

```bash
php artisan db:seed --force
```

Atau jalankan seeder secara individual:

```bash
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=AdminSeeder --force
php artisan db:seed --class=DosenLaboranLabSeeder --force
php artisan db:seed --class=MahasiswaSeeder --force
```

#### Penjelasan Seeder

**PENTING:** RoleSeeder **harus** dijalankan lebih dulu sebelum seeder lainnya, karena tabel `roles` menjadi acuan untuk relasi role pada setiap user.


## 7. Build Frontend

```bash
npm install
npm run build
```


## 11. Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Ringkasan Perintah Berurutan

```bash
# 1. Install paket sistem
sudo apt update
sudo apt install -y php8.2-cli php8.2-fpm php8.2-mbstring php8.2-xml \
  php8.2-bcmath php8.2-curl php8.2-mysql php8.2-zip php8.2-gd php8.2-intl \
  php8.2-readline php8.2-tokenizer mysql-server nodejs

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

# 8. Migrasi database & seeder
php artisan migrate --force
php artisan db:seed --force

# 9. Build frontend
npm install
npm run build

# 10. Optimasi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
