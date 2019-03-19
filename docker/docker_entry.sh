#!/bin/bash
set -e -x
chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
service mysql restart
service apache2 restart
judged

if [ -z "$@" ]; then
	/bin/bash
else
	exec "$@"
fi
