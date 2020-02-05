#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo '=> Running tests on a running environment.'
URL="$(docker-compose port drupal 80)"

echo 'Run some self-tests'
docker-compose exec drupal /bin/bash -c 'drush ev "word_monitor()->selfTest();"'
