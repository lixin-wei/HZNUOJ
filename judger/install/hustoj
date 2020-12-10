#!/bin/bash
#
### BEGIN INIT INFO
# Provides:          hustoj
# Required-Start:    $remote_fs $syslog
# Required-Stop:     $remote_fs $syslog
# Should-Start:      $network $time
# Should-Stop:       $network $time
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: Start and stop the hustoj database server daemon
# Description:       Controls the main MySQL database server daemon "hustojd"
#                    and its wrapper script "judged".
### END INIT INFO
#
set -e
set -u
${DEBIAN_SCRIPT_DEBUG:+ set -v -x}

test -x /usr/bin/judged || exit 0

. /lib/lsb/init-functions

SELF=$(cd $(dirname $0); pwd -P)/$(basename $0)
CONF=/home/judge/etc/judge.conf

# Safeguard (relative paths, core dumps..)
cd /
umask 077

# hustojadmin likes to read /root/.my.cnf. This is usually not what I want
# as many admins e.g. only store a password without a username there and
# so break my scripts.
export HOME=/home/judge

## Checks if there is a server running and if so if it is accessible.
#
# check_dead also fails if there is a lost hustojd in the process list
#
# main()
#

case "${1:-''}" in
  'start')
	# Start daemon
	export LANG=zh_CN.UTF-8
	/usr/bin/judged
	;;
  'stop')
	pkill -9 judged
	;;
  'restart'|'reload'|'force-reload')
	pkill -9 judged
	sleep 3
	/usr/bin/judged
	;;
  'status')
	ps aux|grep judged
  	;;

  *)
	echo "Usage: $SELF start|stop|restart|reload|force-reload|status"
	exit 1
	;;
esac

# Some success paths end up returning non-zero so exit 0 explicitly. See
# bug #739846.
exit 0
