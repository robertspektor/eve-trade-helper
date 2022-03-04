command_test=(php artisan test)

command_test_coverage=(php artisan test --coverage-html coverage)

command_key=(php artisan key:generate)

command_cs=(PHP_CS_FIXER_IGNORE_ENV=1 vendor/bin/php-cs-fixer fix --allow-risky=yes)

command_stan=(php ./vendor/bin/phpstan analyse --memory-limit=2G)

command_queue_work_structure=(php artisan queue:listen --queue=structure_sync)
command_queue_work_order=(php artisan queue:listen --queue=order_sync)
command_queue_work_type=(php artisan queue:listen --queue=type_sync)

command_migrate=(php artisan migrate)

command_seed=(php artisan db:seed)

command_composer_install=(composer install)

command_queue_monitor=(php artisan queue:monitor order_sync,strcture_sync)

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

queue-work-order:
	$(command_queue_work_order)

queue-work-structure:
	$(command_queue_work_structure)

queue-monitor:
	$(command_queue_monitor)

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

docker-seed:
	docker-compose exec evetradehelper_php bash -c "$(command_seed)"

docker-import-structure:
	docker-compose exec evetradehelper_php bash -c "php artisan import:structures"

docker-key:
	docker-compose exec evetradehelper_php bash -c "$(command_key)"

docker-queue-work-order:
	docker-compose exec evetradehelper_php bash -c "$(command_queue_work_order)"

docker-queue-work-structure:
	docker-compose exec evetradehelper_php bash -c "$(command_queue_work_structure)"

docker-queue-work-type:
	docker-compose exec evetradehelper_php bash -c "$(command_queue_work_type)"

docker-connect:
	docker-compose exec evetradehelper_php bash

docker-queue-monitor:
	docker-compose exec evetradehelper_php bash -c "$(command_queue_monitor)"
