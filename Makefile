.PHONY: test
test:
	vendor/bin/phpcs
	vendor/bin/php-cs-fixer fix --dry-run
	vendor/bin/phpunit --coverage-text

.PHONY: fix
fix:
	vendor/bin/phpcbf
	vendor/bin/php-cs-fixer fix

.PHONY: README.md
README.md:
	XDEBUG_MODE=off php -d error_reporting=24575 ./vendor/bin/mddoc
