#!/bin/bash
set -e

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate:fresh --force --seed
php artisan storage:link --force

apache2-foreground
