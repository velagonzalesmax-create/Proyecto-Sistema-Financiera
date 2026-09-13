# Imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y Node.js 18 para Vite/Tailwind
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Instalar extensiones de PHP requeridas por Laravel y MySQL (sin la sintaxis errónea)
RUN docker-php-ext-install pdo_mysql mbstring gd xml

# Habilitar el módulo de reescritura de Apache para Laravel
RUN a2enmod rewrite

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar el proyecto al contenedor
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de PHP y generar assets de Tailwind/Vite
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Redirigir la raíz de Apache a la carpeta public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Otorgar permisos a storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache