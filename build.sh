#!/bin/sh

chown root:www-data -R .
chmod 770 -R storage

docker-compose -f docker-compose-prod.yml up --build -d

docker-compose exec -T php composer install
docker-compose exec -T php php artisan migrate --force

# copy laravel worker
cp laravel-worker.conf /etc/supervisor/conf.d

# restart supervisor
supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*
