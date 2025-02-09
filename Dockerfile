FROM php:8.0-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    intl

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Configurar PHP
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copiar archivos del proyecto
COPY . /var/www/html/

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Instalar dependencias
RUN composer install --no-dev --optimize-autoloader

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exponer puerto 80
EXPOSE 80