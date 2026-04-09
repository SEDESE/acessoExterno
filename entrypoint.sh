#!/bin/bash

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Força a criação do log e garante que o Apache (www-data) tem permissão total sobre a pasta storage
touch /var/www/html/storage/logs/laravel.log
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

exec apache2-foreground