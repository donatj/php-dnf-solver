PHP_VERSION=$(shell php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')

.PHONY: test
test:
	vendor/bin/phpcs
	vendor/bin/php-cs-fixer fix --dry-run
	vendor/bin/phpstan analyse -c phpstan.neon
	if [ "8.1" = "$(PHP_VERSION)" ] ; then \
		XDEBUG_MODE=coverage vendor/bin/phpunit --testsuite library --coverage-text; \
	else \
		XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-text; \
	fi

.PHONY: fix
fix:
	vendor/bin/phpcbf
	vendor/bin/php-cs-fixer fix

.PHONY: README.md
README.md:
	XDEBUG_MODE=off php -d error_reporting=24575 ./vendor/bin/mddoc
