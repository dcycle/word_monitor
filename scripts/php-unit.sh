#!/bin/bash
#
# Run unit tests.
#
set -e

docker run -v "$(pwd)":/app phpunit/phpunit \
  --group word_monitor
