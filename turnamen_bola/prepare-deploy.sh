#!/bin/bash
# ============================================================
# Script Persiapan Deploy - Buat ZIP untuk Upload ke cPanel
# Jalankan dari folder project: bash prepare-deploy.sh
# ============================================================

APP_NAME="turnamen-disdikpora"
OUTPUT="$HOME/Desktop/${APP_NAME}-deploy.zip"

echo "============================================"
echo " Persiapan Deploy - ${APP_NAME}"
echo "============================================"

# Clear all caches
echo "[1/5] Membersihkan cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Install production dependencies only
echo "[2/5] Install composer dependencies (no-dev)..."
composer install --optimize-autoloader --no-dev --quiet

# Build frontend assets
echo "[3/5] Build frontend assets..."
npm run build 2>/dev/null || echo "  (Vite build skipped - assets sudah ada)"

echo "[4/5] Membuat file ZIP untuk upload..."
cd ..
zip -r "${OUTPUT}" turnamen_bola \
    --exclude "turnamen_bola/node_modules/*" \
    --exclude "turnamen_bola/.git/*" \
    --exclude "turnamen_bola/tests/*" \
    --exclude "turnamen_bola/storage/logs/*" \
    --exclude "turnamen_bola/*.zip"

echo "[5/5] Selesai!"
echo ""
echo "File ZIP tersedia di: ${OUTPUT}"
echo "Upload file ini ke cPanel File Manager RumahWeb."
echo "============================================"
