#!/bin/bash
set -e

# Generar clave de aplicación si no está en cache
php artisan key:generate --force || true

# Limpiar y optimizar cachés de Laravel
php artisan config:clear
php artisan cache:clear

# Ejecutar las migraciones a Aiven automáticamente
php artisan migrate --force || true

# Iniciar Apache
exec apache2-foreground