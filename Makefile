.PHONY: setup
setup: build startup vendor

.PHONY: build
build:
	docker-compose build

.PHONY: startup
startup:
	docker-compose up -d

.PHONY: shutdown
shutdown:
	docker-compose down

.PHONY: vendor
vendor:
	docker-compose exec php composer install -d ./web

.PHONY: update-vendor
update-vendor:
	docker-compose exec php composer update -d ./web

.PHONY: test
test:
	docker-compose exec php ./web/bin/phpunit --configuration ./web/phpunit.xml
