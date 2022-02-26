#!/bin/sh

chown root:www-data -R .
chmod 770 -R storage

docker-compose -f docker-compose-prod.yml up --build -d

docker-compose exec -T php composer install
docker-compose exec -T php php artisan migrate --force

#else
#
#
#fi
#   DB_NAME=laravel
#   docker exec db /usr/bin/mysqldump -u root --password=newnew laravel > laravel_initial_setup_backup.sql
#   docker exec -i db mysql -u root --password=newnew laravel < laravel_initial_setup_backup.sql

#    docker exec webserver composer install -n
#    docker exec webserver composer id -n
#    docker exec webserver drush cim -y
#    docker exec webserver drush cex -y
