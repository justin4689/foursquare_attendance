#!/bin/sh
set -e

# Crée le .env depuis les variables Render
cat > /var/www/html/.env << EOF
APP_NAME=FoursquareAttendance
APP_ENV=production
APP_KEY=${APP_KEY}
APP_DEBUG=false
APP_URL=${APP_URL}

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT:-4000}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}

MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync
EOF

# Permissions
chown www-data:www-data /var/www/html/.env
chmod 600 /var/www/html/.env

# Laravel setup
cd /var/www/html

php artisan config:clear
php artisan migrate --force --no-interaction
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf