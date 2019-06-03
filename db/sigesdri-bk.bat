echo off

cd S:\wamp64\bin\mysql\mysql5.7.24\bin
mysqldump -u root --password=dwjtb sigesdri --single-transaction --quick --lock-tables=false > "S:\db-backups\sigesdri-backup-$(date +%F).sql"