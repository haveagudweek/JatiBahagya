# Gunakan PHP + Apache
FROM php:8.2-apache

# Install ekstensi Laravel yang umum
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev libpng-dev libonig-dev libxml2-dev \
    zip && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja
WORKDIR /var/www/html

# Copy semua file project Laravel
COPY . .

# Install dependency Laravel
RUN composer install --no-dev --optimize-autoloader

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Copy config vhost (agar public/ jadi root folder web)
COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Ubah permission untuk folder penting Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Jalankan config cache & migrate saat container jalan
CMD php artisan config:cache && php artisan migrate --force && apache2-foreground
