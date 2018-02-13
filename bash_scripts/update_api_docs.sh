#!/bin/bash

# update_api_docs.sh
# this script will update the API docs
# it's using 'api:generate' command, then copying stikit logo

rm -fr public/docs
php artisan api:generate --middleware='api' --router='laravel'

echo ""
echo -e "${GREEN}API docs updated${NC}"
echo ""
