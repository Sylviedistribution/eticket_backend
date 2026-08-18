#!/bin/sh

set -e

echo "Starting Laravel..."

php artisan config:cache
php artisan route:cache

php-fpm -D

nginx -g "daemon off;"