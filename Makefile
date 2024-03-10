install:
	composer install

gendiff:
	php bin/gendiff.php -f

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin

format:
	composer exec --verbose phpcbf -- --standard=PSR12 src bin