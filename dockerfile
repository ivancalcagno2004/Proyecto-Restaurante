# Imagen base de PHP con Composer y extensiones comunes
FROM php:8.2-fpm

# Instalar dependencias del sistema y extensiones necesarias
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiar los archivos del proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader


# Exponer el puerto que usa Laravel
EXPOSE 8000

# Comando por defecto
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
