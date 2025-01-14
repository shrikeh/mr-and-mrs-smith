#!make

.build-dir:
	bash -c "[[ -d ./build ]] || mkdir ./build";

.composer-%: .build-dir
	docker compose run --remove-orphans --rm --entrypoint="composer $*" "${APP_CONTAINER}";

install:
	$(MAKE) .composer-install;

update:
	$(info [+] Make: Running composer update...)
	$(MAKE) .composer-update;

metrics:
	$(info [+] Make: Generating metrics...)
	$(MAKE) .composer-metrics;

phpcs:
	$(info [+] Make: Running Codesniffer)
	$(MAKE) .composer-phpcs;

behat:
	$(info [+] Make: Running composer behat...)
	$(MAKE) .composer-behat;

fix:
	$(info [+] Make: Running composer fix...)
	$(MAKE) .composer-fix;

phpunit:
	$(info [+] Make: Running composer phpunit...)
	$(MAKE) .composer-phpunit;

psalm:
	$(info [+] Make: Running composer psalm...)
	$(MAKE) .composer-psalm;

phpstan:
	$(info [+] Make: Running composer phpstan...)
	$(MAKE) .composer-phpstan;

infection:
	$(info [+] Make: Running composer infection...)
	$(MAKE) .composer-infection;

.init:
	$(MAKE) .composer-install;

.test:
	$(info [+] Make: 'Running all tests defined in `composer test')
	$(MAKE) .composer-test;

.quality:
	echo 'Checking branch for quality...'
	$(MAKE) .composer-quality;

.craft:
	$(MAKE) .composer-craft;
