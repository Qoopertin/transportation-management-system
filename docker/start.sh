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

echo "Setting permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Generate Nginx config with Railway's PORT
echo "Configuring Nginx to listen on port ${PORT:-8080}..."
envsubst '${PORT}' < /etc/nginx/sites-available/default.template > /etc/nginx/sites-available/default

# Test Nginx configuration
echo "Testing Nginx configuration..."
nginx -t

# Test PHP-FPM
echo "Testing PHP-FPM configuration..."
php-fpm -t

echo "Starting services via Supervisor..."
echo "Nginx will listen on port: ${PORT:-8080}"
# Start supervisor which will manage PHP-FPM and Nginx
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
