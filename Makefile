.PHONY: up
up:
		docker compose up -d

.PHONY: down
down:
		docker compose down

.PHONY: test
test:
	docker exec -it clients-import-service-app-1 /var/www/html/vendor/bin/phpunit /var/www/html/tests --colors=always

.PHONY: migrate
migrate:
	docker exec -it clients-import-service-app-1 php artisan migrate

.PHONY: seed
seed:
	docker exec -it clients-import-service-app-1 php artisan db:seed

.PHONY: build
build: up migrate seed




.DEFAULT_GOAL := up
