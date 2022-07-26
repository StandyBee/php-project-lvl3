start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	npm ci

watch:
	npm run watch

migrate:
	php artisan migrate

test_phpunit:
	composer exec --verbose phpunit tests

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku

lint:
	composer exec phpcs -- --standard=PSR12 app routes tests

lint-fix:
	composer phpcbf app routes tests database lang

test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

install:
	composer install

validate:
	composer validate