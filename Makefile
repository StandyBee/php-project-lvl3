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
	php -r "file_exists('.env') || copy('.env.example', '.env');"
	php artisan key:generate
deploy:
	git push heroku

test_phpunit:
	composer exec --verbose phpunit tests
install:
	composer install
validate:
	composer validate
