#!/bin/bash
set -e

if [ -z $OPEN_API_SPEC_URL ]; then
    echo "Environment variable OPEN_API_SPEC_URL is not set."; exit 1;
fi

if [ -z $CI_COMMIT_REF_NAME ]; then
    echo "Environment variable CI_COMMIT_REF_NAME is not set."; exit 1;
fi

composer update
git checkout -B feature/client-generation
wget --no-check-certificate -cq $OPEN_API_SPEC_URL -O - | jq --indent 4 '.' > resources/api/api-spec.yml
chmod -R 777 resources/api
vendor/bin/openapi-codegen-php-client.sh
git stash -- resources/api/api-spec.yml
if [ -z "$(git status --porcelain)" ]; then echo "No changes found during a new generate."; exit 0; fi
git stash apply
git add .
git commit -m "Automatic update client by CI"
git push origin feature/client-generation:$CI_COMMIT_REF_NAME --force