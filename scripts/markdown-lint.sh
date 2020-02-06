#!/bin/bash
#
# Lint Markdown.
#
set -e

echo '=> Linting markdown.'
echo ' (see https://github.com/dcycle/docker-markdown-lint/blob/master/README.md) '
docker run --rm --entrypoint markdownlint -v $(pwd):/app/code dcycle/markdown-lint /app/code/README.md
