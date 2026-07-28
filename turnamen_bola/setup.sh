#!/bin/bash
# ============================================================
# SETUP SCRIPT - Piala Disdikpora Grassroot Regional Kebumen
# Jalankan sekali saat pertama kali deploy ke server cPanel
# ============================================================

echo "============================================"
echo " Setup Aplikasi Piala Disdikpora Kebumen"
echo "============================================"

# 1. Pastikan file .env ada
if [ ! -f ".env" ]; then
    echo "[1/7] Membuat file .env dari template..."
    cp .env.example .env
else
    echo "[1/7] File .env sudah ada, lewati..."
fi

# 2. Generate application key
echo "[2/7] Generate APP_KEY..."
php artisan key:generate --force

# 3. Buat file SQLite jika belum ada
echo "[3/7] Membuat file database SQLite..."
mkdir -p database
touch database/database.sqlite

# 4. Jalankan migrasi
echo "[4/7] Menjalankan migrasi database..."
php artisan migrate --force

# 5. Jalankan seeder (tanpa data dummy)
echo "[5/7] Menjalankan seeder (admin + event + kategori usia)..."
php artisan db:seed --force

# 6. Buat symlink storage
echo "[6/7] Membuat symbolic link storage..."
php artisan storage:link

# 7. Optimize untuk production
echo "[7/7] Optimasi aplikasi..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "============================================"
echo " SETUP SELESAI!"
echo " Akun Admin:"
echo "  URL: https://pialadisdikporagrasrutregionalkebumen.my.id/admin/login"
echo "  Username: admin@disdikpora.id"
echo "  Password: admin123"
echo " (Segera ganti password setelah login pertama!)"
echo "============================================"
