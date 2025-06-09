# 🚀 Laravel Project Installation Guide

Panduan ini akan membantu kamu men-setup dan menjalankan proyek Laravel secara lokal.

## 📋 Prasyarat

Sebelum memulai, pastikan kamu sudah menginstall:

-   PHP >= 8.1
-   Composer
-   MySQL / MariaDB
-   Git
-   Laravel CLI (opsional)

> 💡 Gunakan [Laragon](https://laragon.org/) atau [XAMPP](https://www.apachefriends.org/index.html) jika kamu butuh development environment cepat di Windows.

---

## 📦 1. Clone Repository

git clone https://github.com/Galsans/Backend-Sistem-Absensi-Lokasi
cd API-Invent

## ⚙ 2. Install Dependency PHP

composer install

## 🔑 3. Salin File .env

cp .env.example .env

# atau

copy .env.example .env

## 🔐 4. Generate App Key, JWT Secret & Storage

php artisan key:generate
php artisan jwt:secret
php artisan Storage:link

## 🗄 5. Jalankan Migrasi dan Seeder

php artisan migrate --seed

## 🏃 6. Jalankan Server Lokal

php artisan serve

### 🔒 Auth dengan JWT

Authorization: Bearer {your_token}
