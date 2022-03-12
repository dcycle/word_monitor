#!/bin/bash
#
# Run tests, meant to be run on CirlceCI.
#
set -e

echo '=> Run fast tests.'
./scripts/test.sh

echo '=> Deploy a Drupal 9 environment.'
./scripts/deploy.sh

echo '=> Tests on Drupal 9 environment.'
./scripts/test-running-environment.sh

echo '=> Browser tests on Drupal 9 environment.'
./scripts/end-to-end-tests.sh

echo '=> Destroy the Drupal 9 environment.'
./scripts/destroy.sh

echo '=>'
echo '=> Done all continuous integration tests for word_monitor!'
echo '=>'
