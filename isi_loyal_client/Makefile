init-test-db:
	php bin/console --env=test doctrine:database:drop --force --if-exists
	php bin/console --env=test doctrine:database:create
	php bin/console --env=test doctrine:migration:migrate --no-interaction
	php bin/console --env=test doctrine:fixtures:load --no-interaction

test:
	bin/console --env=test c:c
	bin/phpunit

run:
	composer install --no-interaction

up:
	docker compose up -d

load-dev-fixtures:
	php bin/console --env=dev doctrine:fixtures:load --no-interaction

analyze:
	vendor/bin/phpstan analyse src --level 9
	vendor/bin/ecs