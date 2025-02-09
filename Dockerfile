# Dockerfile para una aplicación PHP con Apache
#
# Este Dockerfile configura un entorno para ejecutar una aplicación PHP 8.0 con Apache.
# Incluye:
# - PHP 8.0 con Apache
# - Extensiones PHP comunes (mysqli, pdo, zip, intl)
# - Composer para gestión de dependencias
# - Git y utilidades zip
# 
# El contenedor:
# - Usa la imagen base php:8.0-apache
# - Instala dependencias del sistema y extensiones PHP necesarias
# - Configura Apache con mod_rewrite
# - Copia los archivos del proyecto y configura permisos
# - Instala dependencias via Composer
# - Expone el puerto 80 para acceso web
#
# Uso:
# 1. Construir: docker build -t my-php-app .
# 2. Ejecutar: docker run -p 80:80 my-php-app
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