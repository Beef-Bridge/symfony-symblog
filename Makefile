# Var
LOCAL_HOST        = http://127.0.0.1
LOCAL_PORT_APACHE = 8001
LOCAL_PORT_PMA    = 8081

# Executables
EXEC_PHP      = php
COMPOSER      = composer
GIT           = git
YARN          = yarn
NPM           = npm

# Executables: local only
DOCKER        = docker
DOCKER_COMP   = docker-compose

# Alias
SYMFONY       = $(EXEC_PHP) bin/console

# Executables: vendors
PHPUNIT       = ./vendor/bin/phpunit
PHPSTAN       = ./vendor/bin/phpstan
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer
PHPMETRICS    = ./vendor/bin/phpmetrics

# Executable dans un conteneur
EXEC_CONTAINER = $(DOCKER) exec -w /var/www/ www_symblog_youtube
EXEC_PHP_CONTAINER = $(EXEC_CONTAINER) $(EXEC_PHP)
EXEC_SYMFONY_CONTAINER = $(EXEC_PHP_CONTAINER) bin/console
EXEC_PHP_TEST_CONTAINER = $(EXEC_PHP_CONTAINER) bin/phpunit
EXEC_COMPOSER_CONTAINER = $(EXEC_CONTAINER) $(COMPOSER)
EXEC_YARN_CONTAINER = $(EXEC_CONTAINER) $(YARN)
EXEC_NPM_CONTAINER = $(EXEC_CONTAINER) $(NPM)

# Couleurs
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

## —— Help screen ——
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— App ——
init: ## Init the project
	$(MAKE) docker-start
	$(MAKE) composer-install
	@$(call GREEN, "The application is available at : LOCAL_HOST:LOCAL_PORT_APACHE")

## —— Tests ——
tests: ## Run all tests
	$(MAKE) database-init-test
	$(EXEC_PHP_TEST_CONTAINER) bin/phpunit --testdox tests/Unit/
	$(EXEC_PHP_TEST_CONTAINER) bin/phpunit --testdox tests/Functional/
 
database-init-test: ## Init database for test
	$(EXEC_SYMFONY_CONTAINER) d:d:d --force --if-exists --env=test
	$(EXEC_SYMFONY_CONTAINER) d:d:c --env=test
	$(EXEC_SYMFONY_CONTAINER) d:m:m --no-interaction --env=test
	$(EXEC_SYMFONY_CONTAINER) d:f:l --no-interaction --env=test

## —— Symfony ——
cache-clear: ## Clear cache
	$(EXEC_SYMFONY_CONTAINER) cache:clear
fix-perms: ## Fix permissions of all var files
	@chmod -R 777 var/*
purge: ## Purge cache and logs
	@rm -rf var/cache/* var/logs/*

## —— Docker ——
docker-start: ## Start app
	$(DOCKER_COMP) up --detach
docker-down: ## Stop the docker hub
	$(DOCKER_COMP) down --remove-orphans

## —— Composer ——
composer-install: ## Install dependencies
	$(EXEC_COMPOSER_CONTAINER) install --no-progress --prefer-dist --optimize-autoloader

## —— Database ——
db-create: ##  Create database
	$(EXEC_SYMFONY_CONTAINER) doctrine:database:create --if-not-exists

db-update: ## Update database
	$(EXEC_SYMFONY_CONTAINER) doctrine:schema:update --force --dump-sql --complete
	$(EXEC_SYMFONY_CONTAINER) doctrine:migrations:migrate -n

db-validate-schema: ## Valid doctrine mapping
	$(EXEC_SYMFONY_CONTAINER) doctrine:schema:validate --skip-sync
