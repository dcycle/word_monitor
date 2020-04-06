#!/bin/bash
#
# Get a login link for the environment.
#
set -e

# See https://github.com/docker/compose/issues/3352#issuecomment-221526576;
# useful for ./scripts/jenkins/test.sh.
docker-compose exec -T drupal /bin/bash -c "drush -l $(docker-compose port drupal 80) uli"
