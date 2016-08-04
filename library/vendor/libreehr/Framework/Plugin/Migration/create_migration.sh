#!/bin/bash
# HOWTO
# 1 	Run ./create_migration.sh (this script.) The result will be a new file with your current
#		timestamp in the sql/migrations directory.
# 2     Copy that file to your module's directory
# 3		Add your add/remove database modifications to the sql/migrations/migration_*.sql file just
#		as you would with an upgrade or patch.
# 4		Create an instance subclass of \Migration and implement up() and down() methods
#       returning the appropriate files so that your migrations will be run. 
#
# NOTES:
# * Migrations are run in order by file name. Do not change the file name.
# * Migrations current migrations are logged in sql/migrations.log
echo "Enter abslute path to put your migration file..."
read DIRECTORY
mkdir -p $DIRECTORY
CLASS="Migration_$(date +%Y_%m_%d_%H_%M_%S)"
cp migration_template.php "$DIRECTORY/$CLASS.php"
sed -i -e "s/MaxemailMigration/$CLASS/g" $DIRECTORY/$CLASS.php
rm -f "$DIRECTORY/$CLASS.php-e"
echo "Migration $DIRECTORY/$CLASS.php created successfully."
