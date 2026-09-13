#!/bin/bash
set -e

# Ejecutar migración de Laravel
php artisan migrate --force || true

# Iniciar servidor Apache
exec apache2-foreground