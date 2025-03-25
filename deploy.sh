#!/bin/bash

# Script untuk mempersiapkan aplikasi untuk deployment

# 1. Install dependensi composer
echo "Installing composer dependencies..."
composer install --optimize-autoloader --no-dev

# 2. Install dependensi NPM
echo "Installing npm dependencies..."
npm install

# 3. Build asset untuk produksi
echo "Building assets for production..."
npm run build

# 4. Clear cache dan generate cache baru
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Set permission yang benar untuk direktori storage dan bootstrap/cache
echo "Setting correct permissions..."
chmod -R 775 storage bootstrap/cache
chmod -R 775 public/build

echo "==================================="
echo "Application ready for deployment!"
echo "==================================="
echo "Upload all files to your hosting, then run:"
echo "php artisan migrate"
echo "===================================" 