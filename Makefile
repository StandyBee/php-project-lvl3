start:
	php artisan serve
	service postgresql start

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	php artisan serve
	service postgresql start
	php artisan migrate
	php artisan db:seed
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