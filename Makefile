#Makefile
start:
	php artisan serve
lint:
	composer run-script phpcs -- --standard=PSR12 app tests
test:
	php artisan test
test-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml
setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	php artisan db:seed
	npm ci
deploy:
	git push heroku

test_phpunit:
	composer exec --verbose phpunit tests
install:
	composer install
validate:
	composer validate
