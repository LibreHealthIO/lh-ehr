#!/bin/bash
set -euo pipefail

# usage: file_env VAR [DEFAULT]
#    ie: file_env 'XYZ_DB_PASSWORD' 'example'
# (will allow for "$XYZ_DB_PASSWORD_FILE" to fill in the value of
#  "$XYZ_DB_PASSWORD" from a file, especially for Docker's secrets feature)
file_env() {
	local var="$1"
	local fileVar="${var}_FILE"
	local def="${2:-}"
	if [ "${!var:-}" ] && [ "${!fileVar:-}" ]; then
		echo >&2 "error: both $var and $fileVar are set (but are exclusive)"
		exit 1
	fi
	local val="$def"
	if [ "${!var:-}" ]; then
		val="${!var}"
	elif [ "${!fileVar:-}" ]; then
		val="$(< "${!fileVar}")"
	fi
	export "$var"="$val"
	unset "$fileVar"
}

if [[ "$1" == apache2* ]] || [ "$1" == php-fpm ]; then
	if [ "$(id -u)" = '0' ]; then
		case "$1" in
			apache2*)
				user="${APACHE_RUN_USER:-www-data}"
				group="${APACHE_RUN_GROUP:-www-data}"
				;;
			*) # php-fpm
				user='www-data'
				group='www-data'
				;;
		esac
	else
		user="$(id -u)"
		group="$(id -g)"
	fi

	if [ ! -e .htaccess ]; then
		# NOTE: The "Indexes" option is disabled in the php:apache base image
		cat > .htaccess <<- EOF
    # BEGIN LibreHealth
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteBase /
        RewriteRule ^index\.php$ - [L]
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteRule . /index.php [L]
    </IfModule>
    # END LibreHealth
EOF
		chown "$user:$group" .htaccess
	fi

	envs=(
		LIBREHEALTH_DB_HOST
		LIBREHEALTH_DB_PORT
		LIBREHEALTH_DB_USER
		LIBREHEALTH_DB_PASSWORD
		LIBREHEALTH_DB_NAME
	)

	haveConfig=
	for e in "${envs[@]}"; do
		file_env "$e"
		if [ -z "$haveConfig" ] && [ -n "${!e}" ]; then
			haveConfig=1
		fi
	done

	# Control startup order when not using
	#  $ docker run --health-cmd='mysqladmin ping -h"${LIBREHEALTH_DB_HOST}" --silent (...)'
	#  nor health service
	#  more: https://docs.docker.com/compose/startup-order/
	#
	#  This was an express request from community:
	while ! mysqladmin ping -h"${LIBREHEALTH_DB_HOST}" -u"${LIBREHEALTH_DB_USER}" -p"${LIBREHEALTH_DB_PASSWORD}" --silent; do
		>&2 echo "MYSQL host ${LIBREHEALTH_DB_HOST} is unavailable - sleeping ..."
		sleep 1
	done
	>&2 echo "DB is up - continue ..."

	# now that we're definitely done writing configuration, let's clear out the relevant envrionment variables (so that stray "phpinfo()" calls don't leak secrets from our code)
	for e in "${envs[@]}"; do
		unset "$e"
	done
fi

exec "$@"
