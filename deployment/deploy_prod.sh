#!/bin/sh

rsync -azCR --force --delete --progress --exclude-from "./deployment/rsync_exclude.txt" -e "ssh -p 22" ./ root@cdn-orange.com:/var/www2/cdn_server/

ssh root@cdn-orange.com -p 22 "cd /var/www2/cdn_server/ && chown -R 51:root * ; ./console ca:c --env='prod' ; chmod -R 777 app/cache ; chown -R 51:root app/cache ; ./console assets:install --symlink web/ ; ./console assetic:dump --env=prod ; chmod -R 755 web/bundles " ;