#!/bin/bash
#
# Run fast tests.
#
set -e

echo '=> Linting code.'
./scripts/shell-lint.sh
./scripts/yaml-lint.sh
./scripts/php-lint.sh
./scripts/markdown-lint.sh

echo '=> Static analysis.'
./scripts/php-static-analysis.sh

echo '=> Unit tests.'
./scripts/php-unit.sh
