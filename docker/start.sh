#!/bin/sh
set -e

# Crée le .env à partir des variables d'environnement
cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST="${DB_HOST}"
DB_PORT="${DB_PORT:-4000}"
DB_DATABASE="${DB_DATABASE}"
DB_USERNAME="${DB_USERNAME}"
DB_PASSWORD="${DB_PASSWORD}"

MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync
EOF

# Generate app key if not set
php artisan key:generate --force --no-interaction 2>/dev/null || true

# Run migrations
php artisan migrate --force --no-interaction

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start services
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf