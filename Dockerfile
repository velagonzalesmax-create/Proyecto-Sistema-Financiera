FROM php:8.2-apache

# Instalar extensiones necesarias para Laravel y MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring exts gd xml

# Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# Copiar el proyecto al servidor
COPY . /var/www/html

# Configurar el DocumentRoot a la carpeta public/
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Dar permisos a storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

WORKDIR /var/www/html