#!/bin/bash
#before install check DB setting in 
#	judge.conf 
#	hustoj-read-only/web/include/db_info.inc.php
#	and down here
#and run this with root

#CENTOS/REDHAT/FEDORA WEBBASE=/var/www/html APACHEUSER=apache 
WEBBASE=/var/www/html
APACHEUSER=www-data
DBUSER=root
DBPASS=root

printf "Input Database(MySQL) Username:"
read tmp
if test -n "$tmp"
then
    DBUSER="$tmp"
fi
printf "Input Database(MySQL) Password:"
read tmp
if test -n "$tmp"
then
    DBPASS="$tmp"
fi

#try install tools
if uname -a|grep 'Ubuntu\|Debian'
then 
sudo apt-get install make flex g++ clang libmysql++-dev php5 apache2 mysql-server php5-mysql php5-gd php5-cli mono-gmcs subversion
sudo /etc/init.d/mysql start
HTTP_START="sudo /etc/init.d/apache2 restart"

else
sudo yum -y update
sudo yum -y install php httpd php-mysql mysql-server php-xml php-gd gcc-c++  mysql-devel php-mbstring glibc-static flex
sudo /etc/init.d/mysqld start
WEBBASE=/var/www/html
APACHEUSER=apache
HTTP_START="sudo /etc/init.d/httpd restart"
echo "/usr/bin/judged" > judged
fi

sudo svn checkout https://github.com/zhblue/hustoj/trunk/trunk hustoj-read-only

#create user and homedir
sudo  /usr/sbin/useradd -m -u 1536 judge



#compile and install the core
cd hustoj-read-only/core/
sudo ./make.sh
cd ../..
#install web and db
sudo cp -R hustoj-read-only/web $WEBBASE/JudgeOnline
sudo chmod -R 771 $WEBBASE/JudgeOnline
sudo chown -R $APACHEUSER $WEBBASE/JudgeOnline
sudo mysql -h localhost -u$DBUSER -p$DBPASS < db.sql

#create work dir set default conf
sudo    mkdir /home/judge
sudo    mkdir /home/judge/etc
sudo    mkdir /home/judge/data
sudo    mkdir /home/judge/log
sudo    mkdir /home/judge/run0
sudo    mkdir /home/judge/run1
sudo    mkdir /home/judge/run2
sudo    mkdir /home/judge/run3
sudo cp java0.policy  judge.conf /home/judge/etc
sudo chown -R judge /home/judge
sudo chgrp -R $APACHEUSER /home/judge/data
sudo chgrp -R root /home/judge/etc /home/judge/run?
sudo chmod 775 /home/judge /home/judge/data /home/judge/etc /home/judge/run?
#update database account
SED_CMD="s/OJ_USER_NAME=root/OJ_USER_NAME=$DBUSER/g"
SED_CMD2="s/OJ_PASSWORD=root/OJ_PASSWORD=$DBPASS/g"
sed $SED_CMD judge.conf|sed $SED_CMD2 >/home/judge/etc/judge.conf

SED_CMD="s/DB_USER=\\\"root\\\"/DB_USER=\\\"$DBUSER\\\"/g"
SED_CMD2="s/DB_PASS=\\\"root\\\"/DB_PASS=\\\"$DBPASS\\\"/g"

sed $SED_CMD hustoj-read-only/web/include/db_info.inc.php|sed $SED_CMD2 >$WEBBASE/JudgeOnline/include/db_info.inc.php


#boot up judged
sudo cp judged /etc/init.d/judged
sudo chmod +x  /etc/init.d/judged
sudo ln -s /etc/init.d/judged /etc/rc3.d/S93judged
sudo ln -s /etc/init.d/judged /etc/rc2.d/S93judged
sudo /etc/init.d/judged start

$HTTP_START

if uname -a | grep 'Ubuntu\|Debian'
then
   echo "Debian is better :P"
else
   chcon -R -t httpd_sys_content_t /home/judge/
   chcon -R -t httpd_sys_content_t /var/www/html/
fi

echo "Browse http://127.0.0.1/JudgeOnline to check if the installation is working" 

