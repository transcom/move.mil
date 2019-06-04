# script to run after deployment of new image
# script contains commands that always should be run immediately after manually deploying

#!/usr/bin/env bash
container_id=$(docker ps -q)
docker exec -i ${container_id} vendor/bin/drush sset system.maintenance_mode 1
docker exec -i ${container_id} vendor/bin/drush updatedb -y
docker exec -i ${container_id} vendor/bin/drush entity-updates -y
docker exec -i ${container_id} php drupal config:import -y
docker exec -i ${container_id} vendor/bin/drush entity-updates -y
docker exec -i ${container_id} vendor/bin/drush cr -y
docker exec -i ${container_id} vendor/bin/drush sset system.maintenance_mode 0
