#!/bin/bash
#
# Static analysis.
#
set -e

echo '=> Static analysis of code.'
echo 'If you are getting a false negative, use:'
echo ''
echo '// @phpstan-ignore-next-line'
docker run --rm \
  -v "$(pwd)":/var/www/html/modules/custom/word_monitor \
  dcycle/phpstan-drupal:4 \
  -c /var/www/html/modules/custom/word_monitor/scripts/lib/phpstan/phpstan.neon \
  /var/www/html/modules/custom \
  --memory-limit=-1
