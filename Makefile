.PHONY: up
up:
		docker compose up -d

.PHONY: down
down:
		docker compose down

.PHONY: test
test:
	docker exec -it clients-import-service-app-1 /var/www/html/vendor/bin/phpunit /var/www/html/tests --colors=always

.DEFAULT_GOAL := up
