# Imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y Node.js 18
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo_mysql mbstring gd xml

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Instalar dependencias de PHP y generar assets
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Configurar Apache para apuntar a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Permisos totales a storage y bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Comando de inicio: Generar Key, limpiar caché y arrancar Apache
CMD php artisan key:generate --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground