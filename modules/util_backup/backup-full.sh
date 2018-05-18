#!/usr/bin/env bash
#
# Backup libreehr directory and database
#
# LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
# See the Mozilla Public License for more details.
# If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
#
# @package Librehealth EHR
# @author Sam Likins <sam@mi-squared.com>
# @author Tony McCormick <tony@mi-squared.com>
# @link http://librehealth.io
#
# Please help the overall project by sending changes you make to the author and to the LibreEHR community.
#

if [[ $(whoami) != "root" ]]; then
	echo "You must be root to run this script."
	exit 1
fi

# Configure run -defaults if no arguments are passed
LIBREEHR_PATH="${1:-/var/www/html/libreehr}"
LIBREEHR_SITE="${2:-default}"
ARCHIVE_PATH="${3:-/mnt/backups}"
BACKUP_NAME="${4:-emr_backup}"
TEMPDIR="${5:-/mnt/backups/tmp}"

# Include routines during backup when de-identification is enabled
INCLUDE_DE_IDENTIFICATION="false"


#############
# FUNCTIONS #
#############

function display_message {
	echo "# $1"
}

function display_exit {
	local MESSAGE="$1"
	local EXIT_CODE="${2:-1}"

	echo "* ERROR: $MESSAGE"
	exit "$EXIT_CODE"
}

function create_gzip {
	local FILE="$1"

	$CMD_GZIP $FILE

	if [[ ! -f "$FILE.gz" ]]; then
		display_exit "Failed to create GZip file: $FILE.gz"
	fi
}

function create_tar_archive {
	local ARCHIVE_NAME="$1"
	local COMPRESS_METHOD="$2"
	local FILES="$3"

	if [[ "$COMPRESS_METHOD" == "gz" ]]; then
		local OPTIONS="--same-owner --ignore-failed-read -zcphf";
	else
		local OPTIONS="-cpf";
	fi

	$CMD_TAR $OPTIONS $ARCHIVE_NAME $FILES

	if [[ ! -f "$ARCHIVE_NAME" ]]; then
		display_exit "Failed to create TAR archive file: $ARCHIVE_NAME"
	fi
}

function get_libreehr_backup_files {
	# Return all files and directories substituting sites for sites/$LIBREEHR_SITE and removing newlines
	echo "$(ls -1 "$LIBREEHR_PATH" | sed -e "s|sites|sites/$LIBREEHR_SITE|g" | sed ':a;N;$!ba;s/\n/ /g')"
}

function get_sqlconf_value {
	local VALUE=$1
	local SOURCE="$LIBREEHR_PATH/sites/$LIBREEHR_SITE/sqlconf.php"

	if [[ ! -f "$SOURCE" ]]; then
		display_exit "Failed locating libreehr MySQL configuration file for '$LIBREEHR_SITE': $SOURCE" >&2
	fi

	$CMD_PHP -r "require '$SOURCE'; echo \$$VALUE;"
}

function create_files_backup {
	local CPWD="$(pwd)"
	cd "$LIBREEHR_PATH"

	display_message "Generating TAR GZip archive of libreehr files"

	create_tar_archive "$SITE_TAR_GZ" 'gz' "$(get_libreehr_backup_files)"

	display_message "Finished generating TAR GZip"

	cd "$CPWD"
}

function create_database_backup {
	display_message "Retrieving database connection information from libreehr"

	local LIBREEHR_DB_LOGIN="$(get_sqlconf_value "login")"
	local LIBREEHR_DB_PASS="$(get_sqlconf_value "pass")"
	local LIBREEHR_DB_HOST="$(get_sqlconf_value "host")"
	local LIBREEHR_DB_PORT="$(get_sqlconf_value "port")"
	local LIBREEHR_DB_DBASE="$(get_sqlconf_value "dbase")"

	local OPTIONS="-u $LIBREEHR_DB_LOGIN -p$LIBREEHR_DB_PASS"
	local OPTIONS="$OPTIONS -h$LIBREEHR_DB_HOST --port=$LIBREEHR_DB_PORT"
	[[ "$INCLUDE_DE_IDENTIFICATION" == "true" ]] && local OPTIONS="$OPTIONS --routines"
	local OPTIONS="$OPTIONS --opt --quote-names"
	local OPTIONS="$OPTIONS -r $DATABASE_SQL $LIBREEHR_DB_DBASE"

	display_message "Beginning database dump"

	$CMD_MYSQLDUMP $OPTIONS

	if [[ $? -gt 0 ]]; then
		display_exit "MySQL dump of database '$LIBREEHR_DB_DBASE' on '$LIBREEHR_DB_HOST:$LIBREEHR_DB_PORT' with user '$LIBREEHR_DB_LOGIN' failed"
	elif [[ ! -f "$DATABASE_SQL" ]]; then
		display_exit "Failed to create MySQL dump file: $DATABASE_SQL"
	fi

	display_message "Finished database dump"

	display_message "Placing database dump in a GunZip archive"

	create_gzip "$DATABASE_SQL"
}

function create_libreehr_backup {
	mkdir -p "$BACKUP_PATH"

	display_message "Backing up libreehr files"
	create_files_backup

	display_message "Backing up libreehr database"
	create_database_backup

	display_message "Wrapping backup in TAR archive file"
	local CPWD="$(pwd)"
	cd "$TEMP_PATH"

	create_tar_archive "$BACKUP_TAR" '' "$BACKUP_NAME"

	cd "$CPWD"
}

# Define cleanup process
function finish {
	if [[ -d "$TEMP_PATH" ]]; then
		display_message "Removing temporary work directory: $TEMP_PATH"
		rm -fr -- "$TEMP_PATH"
	fi
}


display_message "Initializing libreehr backup: $(date +"%F %r")"


# Test provided paths exists
if [[ ! -d "$LIBREEHR_PATH" ]]; then
	display_exit "libreehr path doesn't exist: $LIBREEHR_PATH"
elif [[ ! -d "$LIBREEHR_PATH/sites/$LIBREEHR_SITE" ]]; then
	display_exit "libreehr site doesn't exist: $LIBREEHR_SITE"
elif [[ ! -d "$ARCHIVE_PATH" ]]; then
	display_exit "Archive path doesn't exist: $ARCHIVE_PATH"
fi


# Determine the path to the systems temporary directory
if [[ -d "$TMPDIR" ]]; then
	TEMP_PATH="$TMPDIR"
elif [[ -d "$TMP" ]]; then
	TEMP_PATH="$TMP"
elif [[ -d /var/tmp ]]; then
	TEMP_PATH=/var/tmp
elif [[ -d /tmp ]]; then
	TEMP_PATH=/tmp
else
	TEMP_PATH="$(pwd)"
fi


################
# START SCRIPT #
################

display_message "Established system temporary directory: $TEMP_PATH"


# Generate a temporary directory to work in
TEMP_PATH="$(mktemp -d --tmpdir="$TEMP_PATH" "libreehr_backup.XXXXXXXX")"

display_message "Created temporary work directory: $TEMP_PATH"


# Register trap cleanup process
trap finish INT TERM HUP EXIT


display_message "Determining necessary file paths and command locations"

BACKUP_PATH="$TEMP_PATH/$BACKUP_NAME"
DATABASE_SQL="$BACKUP_PATH/libreehr.sql"
SITE_TAR_GZ="$BACKUP_PATH/libreehr.tar.gz"
BACKUP_TAR="$BACKUP_PATH.tar"
ARCHIVE_TAR="$ARCHIVE_PATH/$(date +"%Y/%F")-$BACKUP_NAME-$LIBREEHR_SITE.tar"

CMD_GZIP="$(which gzip | head -n1)"
CMD_TAR="$(which tar | head -n1)"
CMD_PHP="$(which php | head -n1)"
CMD_MYSQLDUMP="$(which mysqldump | head -n1)"


display_message "Beginning backup of libreehr components"

create_libreehr_backup


if [[ -f "$BACKUP_TAR" ]]; then
	if [[ ! -d "$(dirname "$ARCHIVE_TAR")" ]]; then
		mkdir -p "$(dirname "$ARCHIVE_TAR")"
	fi

	display_message "Moving generated backup to archive location: $ARCHIVE_TAR"

	mv "$BACKUP_TAR" "$ARCHIVE_TAR"
else
	display_exit "Missing backup tar file: $BACKUP_TAR"
fi

display_message "Finalized libreehr backup: $(date +"%F %r")"
