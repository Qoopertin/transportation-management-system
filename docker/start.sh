#!/bin/bash

set -e

echo "===== TMS Application Startup ====="
echo "Current directory: $(pwd)"
echo "User: $(whoami)"
echo "PHP version: $(php --version | head -n 1)"

# Wait for database to be ready
echo "Waiting for database to be ready..."
sleep 10

# Test database connection
echo "Testing database connection..."
php /var/www/artisan db:show || echo "Warning: Could not connect to database yet"

# Run migrations
echo "Running database migrations..."
php /var/www/artisan migrate --force --no-interaction || {
    echo "ERROR: Migration failed!"
    php /var/www/artisan --version
    exit 1
}

# Clear and cache config
echo "Optimizing application..."
php /var/www/artisan config:cache || echo "Warning: config:cache failed"
php /var/www/artisan route:cache || echo "Warning: route:cache failed"
php /var/www/artisan view:cache || echo "Warning: view:cache failed"

# Ensure proper permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Test Nginx configuration
echo "Testing Nginx configuration..."
nginx -t

# Test PHP-FPM
echo "Testing PHP-FPM configuration..."
php-fpm -t

echo "Starting services via Supervisor..."
# Start supervisor which will manage PHP-FPM and Nginx
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
