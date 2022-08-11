
start:
	php artisan serve

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	npm ci

migrate:
	php artisan migrate

log:
	tail -f storage/logs/laravel.log

test:
	php artisan test

deploy:
	git push heroku

lint:
	composer run-script phpcs -- --standard=PSR12 app tests

lint-fix:
	composer phpcbf
