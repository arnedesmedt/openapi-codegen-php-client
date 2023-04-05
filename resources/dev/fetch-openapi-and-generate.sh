#!/bin/bash
set -e

composer update
git checkout -B client-generation
wget --no-check-certificate -cq $OPEN_API_SPEC_URL -O - | jq --indent 4 '.' > resources/api/api-spec.yml
chmod -R 777 resources/api
./openapi-codegen-php-client.sh
git status --porcelain
if [ -z "$(git status --porcelain)" ]; then exit 0; fi
git stash apply
git add .
git commit -m "Automatic update client by CI"
git push origin client-generation:$CI_COMMIT_REF_NAME --force