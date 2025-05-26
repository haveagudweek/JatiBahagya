set -e
echo "Running entrypoint tasks..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
echo "Entrypoint tasks complete. Starting server."
exec "$@"