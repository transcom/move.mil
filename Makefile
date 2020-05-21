include .env

.PHONY: up down stop prune ps shell dbdump dbrestore uli cim cex

default: up

up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker-compose pull
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
	docker exec -ti $(shell docker ps --filter name='$(PROJECT_NAME)_php' --format "{{ .ID }}") sh

dbdump:
	@echo "Creating Database Dump for $(PROJECT_NAME)..."
	docker-compose run --rm php drupal database:dump --file=../mariadb-init/restore.sql --gz

dbrestore:
	@echo "Restoring database..."
	docker-compose run --rm php drupal database:connect < mariadb-init/restore.sql.gz

uli:
	@echo "Getting admin login"
	docker-compose run --rm php drush user:login --uri="$(PROJECT_BASE_URL)":8000

cim:
	@echo "Importing Configuration"
	docker-compose run --rm php drupal config:import --directory=../config/sync

	@echo "Clearing Drupal Caches"
	@docker-compose run --rm php drupal cache:rebuild all

cex:
	@echo "Exporting Configuration"
	docker-compose run --rm php drupal config:export --directory=../config/sync

gm:
	@echo "Displaying Generate Module UI"
	docker-compose run --rm php drupal generate:module

install:
	@echo "Installing dependencies"
	docker-compose run --rm php composer install --prefer-source

cr:
	@echo "Clearing Drupal Caches"
	docker-compose run --rm php drupal cache:rebuild all

logs:
	@echo "Displaying past containers logs"
	docker-compose logs

logsf:
	@echo "Follow containers logs output"
	docker-compose logs -f

parser:
	@echo "Parsing move.mil office locations data to Drupal content."
	docker-compose run --rm php drupal parser

dbclient:
	@echo "Opening Move.mil DB client"
	docker-compose run --rm php drupal database:client

tools:
	@echo "Building our custom ReactJS tools"
	@cd ./web/modules/custom/react_tools/tools/; npm install && npm run build
	@echo "Clearing Drupal Caches"
	@docker-compose run --rm php drupal cache:rebuild all

updatedb:
	@echo "Updating DB schema to match with Drupal's core and modules updates."
	@docker-compose run --rm php drush updatedb -y
	@docker-compose run --rm php drupal cache:rebuild all

setup:
	@echo " >> Setting up local move.mil"
	@echo "> [STEP 1/9] Removing containers for $(PROJECT_NAME)..."
	@docker-compose down -v
	@echo "> [STEP 2/9] Starting up containers for for $(PROJECT_NAME)..."
	docker-compose pull
	docker-compose up -d --remove-orphans
	@echo "> [STEP 3/9] Installing dependencies"
	make install
	@echo "> [STEP 4/9] Building site custom theme"
	cd ./web/themes/custom/move_mil/; npm install && npm run build
	@echo "> [STEP 5/9] Building our custom ReactJS tools"
	cd ./web/modules/custom/react_tools/tools/; npm install && npm run build
	make cr
	@echo "> [STEP 6/9] Updating DB schema to match with Drupal's core and modules updates."
	make updatedb
	@echo "> [STEP 7/9] Importing Configuration"
	make cim
	@echo "> Done! Yes... done."
