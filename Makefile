#!make

SHELL:=/usr/bin/env sh
ROOT_DIR:=$(shell dirname $(realpath $(firstword $(MAKEFILE_LIST))))

.EXPORT_ALL_VARIABLES:
.ONESHELL:
.DEFAULT: help
.PHONY: help
ifndef VERBOSE
.SILENT:
endif

ifneq (,$(wildcard './.env'))
-include .env;
endif

APP_CONTAINER:=app

-include dev/make/php.mk

login: .env-file
	env;
	$(info [+] Make: Log in to Docker container ${APP_CONTAINER}.)
	docker compose --env-file ./.env --env-file ./.env.local run --entrypoint=/usr/bin/bash "${APP_CONTAINER}";

up: .env-file
	docker compose --env-file ./.env --env-file ./.env.local up --detach --remove-orphans

stop:
	docker compose stop

build: stop
	docker compose --env-file ./.env --env-file ./.env.local  build

mac:
	brew bundle install

.env-file:
	bash -c "[ -f './.env.local' ] || ${MAKE} .create-env";

.create-env:
	$(info [+] Make: No local .env file so creating)
	${MAKE} .auth

.crafting:
	@echo "\033[92mCrafting excellence...\033[0m"

.direnv:
	direnv allow;

setup: ENV_LOCAL = 'dev'
setup: .direnv mac init

init: .init example
test:  .test
quality: .env .quality
craft: .crafting .craft

run: install
	$(info [+] Make: Running command `shrikeh:test` inside Docker container)
	docker compose run --env-file .env --env-file .env.local --rm "${APP_CONTAINER}";
