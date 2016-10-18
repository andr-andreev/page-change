install:
	composer global require "fxp/composer-asset-plugin:^1.2.0"
	composer update
	php yii migrate

autoload:
	composer dump-autoload

lint:
	composer exec 'phpcs --standard=PSR2 src tests web'

test:
	composer exec 'phpunit tests'
