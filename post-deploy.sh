#!/bin/bash
# script to run after deployment of new image
# script contains commands that always should be run immediately after manually deploying

echo 'Setting site in maintenance mode...'
vendor/bin/drush sset system.maintenance_mode 1
echo 'Updating database...'
vendor/bin/drush updatedb -y
echo 'Importing configuration changes...'
vendor/bin/drupal config:import -y
echo 'Rebuilding cache...'
vendor/bin/drush cr -y
echo 'Exiting maintenance mode...'
vendor/bin/drush sset system.maintenance_mode 0
