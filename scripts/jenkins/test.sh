#!/bin/bash
#
# Run tests on a Docker host. Requirements:
# * https://github.com/dcycle/docker-digitalocean-php.
# * the word-monitor droplet should be deleted in "Post-build Actions".
# * DOCKERHOSTUSER, DOCKERHOSTUSER set using Jenkins's
#   /credentials/store/system/domain/_/ section.
#
set -e

if [ -z "$DOCKERHOSTUSER" ] || [ -z "$DOCKERHOST" ]; then
  >&2 echo "Please configure DOCKERHOSTUSER and DOCKERHOST using"
  >&2 echo "Jenkins secrets (credentials) and export."
  exit 1
fi

# Create a droplet
DROPLET_NAME=word-monitor
PRIVATE_IP=$(ssh "$DOCKERHOSTUSER"@"$DOCKERHOST" \
  "./digitalocean/scripts/new-droplet.sh $DROPLET_NAME")
# https://github.com/dcycle/docker-digitalocean-php#public-vs-private-ip-addresses
IP=$(ssh "$DOCKERHOSTUSER"@"$DOCKERHOST" "./digitalocean/scripts/list-droplets.sh" |grep "$PRIVATE_IP" --after-context=10|tail -1|cut -b 44-)
echo "Created Droplet at $IP"
sleep 90
ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \
  root@"$IP" \
  "git clone http://github.com/dcycle/word_monitor && \
  cd word_monitor && \
  ./scripts/ci.sh"
