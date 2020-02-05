#!/bin/bash
#
# Be ready for Drupal 9.
#
set -e

echo "=> Identify deprecated code so we're ready for Drupal 9"
docker run --rm -v "$(pwd)":/var/www/html/modules/word_monitor dcycle/drupal-check:1.2019-12-30-21-59-43-UTC word_monitor/src
