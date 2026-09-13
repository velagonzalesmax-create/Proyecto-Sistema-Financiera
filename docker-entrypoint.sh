#!/bin/bash
set -e

php artisan key:generate --force || true
php artisan config:clear
php artisan cache:clear
php artisan migrate --force || true

exec apache2-foreground