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
EXEC_COMPOSER_CONTAINER = $(EXEC_CONTAINER) $(COMPOSER)
EXEC_YARN_CONTAINER = $(EXEC_CONTAINER) $(YARN)
EXEC_NPM_CONTAINER = $(EXEC_CONTAINER) $(NPM)

# Couleurs
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

cache-clear: ## Clear cache
	$(EXEC_SYMFONY_CONTAINER) cache:clear
