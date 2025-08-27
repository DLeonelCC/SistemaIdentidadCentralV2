FROM php:8.2-fpm

# Install dependencies
RUN apt update && apt install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libpq-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Clear cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Exponer puerto
EXPOSE 8000

# Establecer el directorio de trabajo
WORKDIR /var/www

# Copiar el código de la aplicación
COPY . .

# Instalar dependencias de Laravel
RUN composer install

# Comando para iniciar el servidor de Laravel
CMD ["sh", "-c", "php artisan storage:link && php artisan serve --host=0.0.0.0 --port=8000"]
