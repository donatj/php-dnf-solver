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
	vendor/bin/mddoc
