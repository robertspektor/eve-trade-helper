command_test=(php artisan test)

command_test_coverage=(php artisan test --coverage-html coverage)

command_key=(php artisan key:generate)

command_cs=(PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --allow-risky=yes)

command_stan=(php ./vendor/bin/phpstan analyse --memory-limit=2G)

command_queue_work_local=(DB_HOST=127.0.0.1 DB_PORT=3190 php artisan queue:work)
command_queue_work=(php artisan queue:work)

command_migrate=(php artisan migrate)
command_migrate_local=(DB_HOST=127.0.0.1 DB_PORT=3190 php artisan migrate)

command_composer_install=(composer install)

all: cs stan test

docker-all: docker-up docker-cs docker-stan docker-test

docker-complete-installation: docker-composer-install docker-key docker-migrate docker-all

composer-install:
	$(command_composer_install)

test:
	$(command_test)

test-coverage:
	$(command_test_coverage)

cs:
	$(command_cs)

stan:
	$(command_stan)

queue-work:
	$(command_queue_work_local)

docker-test:
	docker-compose exec evetradehelper_php bash -c "$(command_test)"

docker-test-coverage:
	docker-compose exec evetradehelper_php bash -c "$(command_test_coverage)"

docker-cs:
	docker-compose exec evetradehelper_php bash -c "$(command_cs)"

docker-stan:
	docker-compose exec evetradehelper_php bash -c "$(command_stan)"

docker-up:
	docker-compose up -d

docker-composer-install: docker-up
	docker-compose exec evetradehelper_php bash -c "$(command_composer_install_app)"
	docker-compose exec evetradehelper_php bash -c "$(command_composer_install_cs)"
	docker-compose exec evetradehelper_php bash -c "$(command_composer_install_stan)"

docker-migrate:
	docker-compose exec evetradehelper_php bash -c "$(command_migrate)"

docker-key:
	docker-compose exec evetradehelper_php bash -c "$(command_key)"

docker-queue-work:
	docker-compose exec evetradehelper_php bash -c "$(command_queue_work)"

docker-connect:
	docker-compose exec evetradehelper_php bash
