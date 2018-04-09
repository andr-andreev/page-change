install:
	composer install
	php yii migrate

autoload:
	composer dump-autoload

lint:
	composer exec 'phpcs --standard=PSR2 src tests web'
