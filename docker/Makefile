SERVICE=holded-service

ssh:
	docker compose exec $(SERVICE) bash

logs:
	docker compose logs -f $(SERVICE)

build:
	docker compose build

up:
	docker compose up -d

down:
	docker compose down

composer-update:
	docker-compose exec $(SERVICE) composer update

composer-install:
	docker-compose exec $(SERVICE) composer install

test:
	docker compose exec $(SERVICE) bin/phpunit

test-dox:
	docker compose exec $(SERVICE) bin/phpunit --testdox

