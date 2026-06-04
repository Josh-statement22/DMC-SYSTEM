#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/dmc-erp}"
PHP_USER="${PHP_USER:-www-data}"
PHP_GROUP="${PHP_GROUP:-www-data}"

cd "$APP_DIR"

echo "Ensuring Laravel writable directories exist..."
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

echo "Fixing ownership and permissions for storage and bootstrap/cache..."
chown -R "$PHP_USER:$PHP_GROUP" storage bootstrap/cache
find storage bootstrap/cache -type d -exec chmod 775 {} \;
find storage bootstrap/cache -type f -exec chmod 664 {} \;

echo "Removing generated bootstrap cache files..."
rm -f bootstrap/cache/*.php

echo "Clearing Laravel caches as $PHP_USER..."
sudo -u "$PHP_USER" php artisan optimize:clear
sudo -u "$PHP_USER" php artisan config:clear
sudo -u "$PHP_USER" php artisan cache:clear
sudo -u "$PHP_USER" php artisan view:clear
sudo -u "$PHP_USER" php artisan route:clear

echo "Regenerating package discovery..."
sudo -u "$PHP_USER" php artisan package:discover --ansi

echo "Verifying Blade compile directory and view binding..."
sudo -u "$PHP_USER" php -r '
$dir = getcwd()."/storage/framework/views";
var_dump(["views_dir" => $dir, "is_dir" => is_dir($dir), "is_writable" => is_writable($dir)]);
$file = tempnam($dir, "probe_");
var_dump(["tempnam" => $file]);
if ($file === false || ! str_starts_with($file, $dir)) {
    fwrite(STDERR, "tempnam did not create the file inside storage/framework/views\n");
    exit(1);
}
unlink($file);
require "vendor/autoload.php";
$app = require "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
var_dump(["view_bound" => $app->bound("view"), "view_class" => get_class($app->make("view"))]);
'

echo "Warming production caches..."
sudo -u "$PHP_USER" php artisan config:cache
sudo -u "$PHP_USER" php artisan view:cache
sudo -u "$PHP_USER" php artisan route:cache
sudo -u "$PHP_USER" php artisan event:cache

echo "Reloading PHP-FPM and Nginx..."
systemctl reload php8.3-fpm
systemctl reload nginx

echo "Recovery complete."
