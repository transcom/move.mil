# script to run after deployment of new image
# script contains commands that always should be run immediately after deploying

commands:
    create_post_dir:
        command: "mkdir /opt/elasticbeanstalk/hooks/appdeploy/post"
        ignoreErrors: true
files:
    "/opt/elasticbeanstalk/hooks/appdeploy/post/99-drupal-updates.sh":
    mode: "000755"
    owner: root
    group: root
    contents: |
        #!/usr/bin/env bash
        container_id=$(sudo docker ps -q)
        sudo docker exec -it ${container_id} vendor/bin/drush updatedb -y
        sudo docker exec -it ${container_id} vendor/bin/drush cim -y
        sudo docker exec -it ${container_id} vendor/bin/drush cim -y --partial --source=modules/custom/custom_move_mil_menus/config/install/
        sudo docker exec -it ${container_id} vendor/bin/drush cr -y
