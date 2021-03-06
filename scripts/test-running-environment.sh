#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo 'Run some self-tests'
docker-compose exec -T drupal /bin/bash -c 'drush ev "word_monitor()->selfTest();"'
