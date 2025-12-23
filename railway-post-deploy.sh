#!/bin/bash

# Railway post-deploy script
# This runs after deployment to set up the application

echo "Running post-deployment tasks..."

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Seed database (only on first deploy)
if [ "$SEED_DATABASE" = "true" ]; then
    php artisan db:seed --force
fi

echo "Post-deployment tasks completed!"
