FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip \
    libpq-dev \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/html
COPY . .

# Instalar dependencias de Laravel
RUN curl -sS https://getcomposer.org/installer | php && \
    php composer.phar install --no-dev --optimize-autoloader

# Agregar esta parte
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - && \
    apt-get install -y nodejs && \
    npm ci && \
    npm run build

EXPOSE 8000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
