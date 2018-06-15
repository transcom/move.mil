include .env

.PHONY: up down stop prune ps shell dbdump dbrestore uli cim cex

default: up

up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker-compose pull --parallel
	docker-compose up -d --remove-orphans

down: stop

stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker-compose stop

prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker-compose down -v

ps:
	@docker ps --filter name="$(PROJECT_NAME)*"

shell:
	docker exec -ti "$(shell docker ps --filter name="$(PROJECT_NAME)_php" --format "{{ .ID }}") sh"

dbdump:
	@echo "Creating Database Dump for $(PROJECT_NAME)..."
	docker-compose run php drupal database:dump --file=../mariadb-init/restore.sql --gz

dbrestore:
	@echo "Restoring database..."
	docker-compose run php drupal database:connect < mariadb-init/restore.sql.gz

uli:
	@echo "Getting admin login"
	docker-compose run php drush user:login --uri="$(PROJECT_BASE_URL)":8000

cim:
	@echo "Importing Configuration"
	docker-compose run php drupal config:import -y

cex:
	@echo "Exporting Configuration"
	docker-compose run php drupal config:export -y

gm:
	@echo "Displaying Generate Module UI"
	docker-compose run php drupal generate:module

menu-update:
	@echo "Updating site menus"
	docker-compose run php drush cim -y --partial --source=modules/custom/custom_move_mil_menus/config/install/
	docker-compose run php drupal cache:rebuild all

composer:
	@echo "Installing dependencies"
	docker-compose run php composer install --prefer-source

cr:
	@echo "Clearing Drupal Caches"
	docker-compose run php drupal cache:rebuild all

logs:
	@echo "Displaying past containers logs"
	docker-compose logs

logsf:
	@echo "Follow containers logs output"
	docker-compose logs -f

parser:
	@echo "Parsing move.mil office locations data to Drupal content."
	docker-compose run php drupal parser

dbclient:
	@echo "Opening Move.mil DB client"
	docker-compose run php drupal database:client

build-tools:
	@echo "Building our custom tools..."
	
	@echo "Building our weight estimator..."
	@if [ ! -d "./web/modules/custom/react_tools/tools/react-weight-estimator/src/localcss/" ]; then echo "Creating Directory..." && cd ./web/modules/custom/react_tools/tools/react-weight-estimator/src/ && mkdir localcss; fi
	@cd ./web/modules/custom/react_tools/tools/react-weight-estimator/src/sass/; sass main.scss ../localcss/main.css
	@cd ./web/modules/custom/react_tools/tools/react-weight-estimator/; npm install
	@cd ./web/modules/custom/react_tools/tools/react-weight-estimator/; npm run build

	@echo "Building our locator map..."
	@if [ ! -d "./web/modules/custom/react_tools/tools/react-locator-map/src/localcss/" ]; then echo "Creating Directory..." && cd ./web/modules/custom/react_tools/tools/react-locator-map/src/ && mkdir localcss; fi
	@cd ./web/modules/custom/react_tools/tools/react-locator-map/src/sass/; sass main.scss ../localcss/main.css
	@cd ./web/modules/custom/react_tools/tools/react-locator-map/; npm install
	@cd ./web/modules/custom/react_tools/tools/react-locator-map/; npm run build

	@echo "Building our ppm tool..."
	@if [ ! -d "./web/modules/custom/react_tools/tools/react-ppm-tool/src/localcss/" ]; then echo "Creating Directory..." && cd ./web/modules/custom/react_tools/tools/react-ppm-tool/src/ && mkdir localcss; fi
	@cd ./web/modules/custom/react_tools/tools/react-ppm-tool/src/sass/; sass main.scss ../localcss/main.css
	@cd ./web/modules/custom/react_tools/tools/react-ppm-tool/; npm install
	@cd ./web/modules/custom/react_tools/tools/react-ppm-tool/; npm run build

	@echo "Building our entitlements page..."
	@if [ ! -d "./web/modules/custom/react_tools/tools/react-entitlements-page/src/localcss/" ]; then echo "Creating Directory..." && cd ./web/modules/custom/react_tools/tools/react-entitlements-page/src/ && mkdir localcss; fi
	@cd ./web/modules/custom/react_tools/tools/react-entitlements-page/src/sass/; sass main.scss ../localcss/main.css
	@cd ./web/modules/custom/react_tools/tools/react-entitlements-page/; npm install
	@cd ./web/modules/custom/react_tools/tools/react-entitlements-page/; npm run build

	@echo "Clearing Drupal Caches"
	@docker-compose run php drupal cache:rebuild all
