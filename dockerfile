# Imagen base con PHP y extensiones necesarias
FROM php:8.2-fpm

# Instalar dependencias del sistema y Composer
RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Copiar el código del proyecto
WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN composer install --no-dev --optimize-autoloader

# Generar clave de Laravel (Render usa variables, pero sirve como fallback)
RUN php artisan key:generate --force

# Exponer el puerto usado por Render
EXPOSE 10000

# Comando para iniciar Laravel
CMD php artisan serve --host=0.0.0.0 --port=10000
