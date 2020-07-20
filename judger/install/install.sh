#!/bin/bash
#before install check DB setting in 
#	judge.conf 
#	hustoj-read-only/web/include/db_info.inc.php
#	and down here
#and run this with root

#CENTOS/REDHAT/FEDORA WEBBASE=/var/www/html APACHEUSER=apache 
WEBBASE=/var/www/
APACHEUSER=www-data
reset
echo ""
echo " ██      ██ ████████ ████     ██ ██     ██   ███████        ██"
echo "░██     ░██░░░░░░██ ░██░██   ░██░██    ░██  ██░░░░░██      ░██"
echo "░██     ░██     ██  ░██░░██  ░██░██    ░██ ██     ░░██     ░██"
echo "░██████████    ██   ░██ ░░██ ░██░██    ░██░██      ░██     ░██"
echo "░██░░░░░░██   ██    ░██  ░░██░██░██    ░██░██      ░██     ░██"
echo "░██     ░██  ██     ░██   ░░████░██    ░██░░██     ██  ██  ░██"
echo "░██     ░██ ████████░██    ░░███░░███████  ░░███████  ░░█████ "
echo "░░      ░░ ░░░░░░░░ ░░      ░░░  ░░░░░░░    ░░░░░░░    ░░░░░  "
echo ""
#try install tools
echo 'mysql-server-5.5 mysql-server/root_password password ""' | sudo debconf-set-selections
echo 'mysql-server-5.5 mysql-server/root_password_again password ""' | sudo debconf-set-selections
deps="make flex g++ clang libmysql++-dev php7.0 apache2 mysql-server libapache2-mod-php7.0 php7.0-mysql php7.0-mbstring php7.0-gd php7.0-cli php-xml mono-mcs subversion libexplain-dev php-zip"
apt-get update
apt-get -y install $deps
apt-get purge -y --auto-remove $buildDeps
apt-get clean
DBUSER=`cat /etc/mysql/debian.cnf |grep user|head -1|awk  '{print $3}'`
DBPASS=`cat /etc/mysql/debian.cnf |grep password|head -1|awk  '{print $3}'`
/etc/init.d/mysql start

#set up database
mysql -u$DBUSER -p$DBPASS < db.sql

#create user and homedir
/usr/sbin/useradd -m -u 1536 judge

#compile and install the core
cd ../core/
bash ./make.sh
cd ../../..

#install web and db
rm -R $WEBBASE
cp -R HZNUOJ/. $WEBBASE
sed -i "s/DB_USER=\"root\"/DB_USER=\"$DBUSER\"/g" $WEBBASE/web/OJ/include/static.php
sed -i "s/DB_PASS=\"root\"/DB_PASS=\"$DBPASS\"/g" $WEBBASE/web/OJ/include/static.php

#create upload dir
mkdir -p $WEBBASE/web/OJ/upload
chmod 770 $WEBBASE/web/OJ/upload
chgrp -R $APACHEUSER $WEBBASE/web/OJ/upload

#create work dir set default conf
mkdir -p /home/judge
mkdir -p /home/judge/etc

#make data for problem 1000
mkdir -p /home/judge/data/1000
pushd /home/judge/data/1000
    echo "1 2" > sample0.in
    echo "3" > sample0.out
    echo "6 10" > test0.in
    echo "16" > test0.out
    echo "6 9" > test1.in
    echo "15" > test1.out
    echo "0 0" > test2.in
    echo "0" > test2.out
popd

mkdir -p /home/judge/log
mkdir -p /home/judge/run0
mkdir -p /home/judge/run1
mkdir -p /home/judge/run2
mkdir -p /home/judge/run3
cd HZNUOJ/judger/install
cp java0.policy  judge.conf /home/judge/etc
sed -i "s/OJ_USER_NAME=root/OJ_USER_NAME=$DBUSER/g" /home/judge/etc/judge.conf
sed -i "s/OJ_PASSWORD=root/OJ_PASSWORD=$DBPASS/g" /home/judge/etc/judge.conf
chown -R judge /home/judge
chgrp -R $APACHEUSER /home/judge/data
chgrp -R root /home/judge/etc /home/judge/run?
chmod 775 -R /home/judge /home/judge/data /home/judge/etc /home/judge/run?

#boot up judged
cp judged /etc/init.d/judged
chmod +x  /etc/init.d/judged
ln -s /etc/init.d/judged /etc/rc3.d/S93judged
ln -s /etc/init.d/judged /etc/rc2.d/S93judged

judged
# change apache server root to /var/www/web
sed -i -e 's/\/var\/www\/html/\/var\/www\/web/g' /etc/apache2/sites-available/000-default.conf
/etc/init.d/apache2 restart

reset
echo ""
echo " ██      ██ ████████ ████     ██ ██     ██   ███████        ██"
echo "░██     ░██░░░░░░██ ░██░██   ░██░██    ░██  ██░░░░░██      ░██"
echo "░██     ░██     ██  ░██░░██  ░██░██    ░██ ██     ░░██     ░██"
echo "░██████████    ██   ░██ ░░██ ░██░██    ░██░██      ░██     ░██"
echo "░██░░░░░░██   ██    ░██  ░░██░██░██    ░██░██      ░██     ░██"
echo "░██     ░██  ██     ░██   ░░████░██    ░██░░██     ██  ██  ░██"
echo "░██     ░██ ████████░██    ░░███░░███████  ░░███████  ░░█████ "
echo "░░      ░░ ░░░░░░░░ ░░      ░░░  ░░░░░░░    ░░░░░░░    ░░░░░  "
echo ""
echo "OJ Configuration:"
echo ""
printf "1-Please input OJ's name, press Enter for default name(argument:\$OJ_NAME): "
read ojname
if test "$ojname" != ""
then
    sed -i "s/OJ_NAME=\"HZNUOJ\"/OJ_NAME=\"$ojname\"/g" $WEBBASE/web/OJ/include/static.php
fi
echo ""
echo "2-Please select the UI language.(argument:\$OJ_LANG)"
echo "  1) Chinese"
echo "  2) English"
temp=0
while test $temp != 1 -a $temp != 2
do
    printf "#? "
    read temp
done
if test $temp = 1
then
    sed -i "s/OJ_LANG=\"en\"/OJ_LANG=\"cn\"/g" $WEBBASE/web/OJ/include/static.php
else
    sed -i "s/OJ_LANG=\"cn\"/OJ_LANG=\"en\"/g" $WEBBASE/web/OJ/include/static.php
fi
echo ""
echo "3-Please select running mode.(argument:OJ_OI_MODE)"
echo "  1)  OI Mode (Middle school)"
echo "  2) ACM Mode (University)"
temp=0
while test $temp != 1 -a $temp != 2
do
    printf "#? "
    read temp
done
if test $temp = 1
then
    sed -i "s/OJ_OI_MODE=0/OJ_OI_MODE=1/g" /home/judge/etc/judge.conf
else
    sed -i "s/OJ_OI_MODE=1/OJ_OI_MODE=0/g" /home/judge/etc/judge.conf
fi
echo ""
echo "4-Please select trun on/off the code share mode.(argument:\$OJ_AUTO_SHARE)"
echo "  1) Trun on  (All of users are able to view all submissions after solving this problem.)"
echo "  2) Trun off (Only administrators are able to view all submissions.)"
temp=0
while test $temp != 1 -a $temp != 2
do
    printf "#? "
    read temp
done
if test $temp = 2
then
    sed -i "s/OJ_AUTO_SHARE=true/OJ_AUTO_SHARE=false/g" $WEBBASE/web/OJ/include/static.php
else
    sed -i "s/OJ_AUTO_SHARE=false/OJ_AUTO_SHARE=true/g" $WEBBASE/web/OJ/include/static.php
fi
echo ""
echo "5-Please select trun on/off show the WA/CE information in reinfo/ceinfo page.(argument:\$OJ_SHOW_DIFF)"
echo "1) Trun on  (All of users are able to view the WA/CE information of their own code.)"
echo "2) Trun off (Only administrators are able to view the WA/CE information.)"
temp=0
while test $temp != 1 -a $temp != 2
do
    printf "#? "
    read temp
done
if test $temp = 2
then
    sed -i "s/OJ_SHOW_DIFF=true/OJ_SHOW_DIFF=false/g" $WEBBASE/web/OJ/include/static.php
else
    sed -i "s/OJ_SHOW_DIFF=false/OJ_SHOW_DIFF=true/g" $WEBBASE/web/OJ/include/static.php
fi
echo ""
echo "6-Please select trun on/off source code similarity detection.(argument:\$OJ_SIM, OJ_SIM_ENABLE)"
echo "1) Trun on"
echo "2) Trun off"
temp=0
while test $temp != 1 -a $temp != 2
do
    printf "#? "
    read temp
done
if test $temp = 2
then
    sed -i "s/OJ_SIM=true/OJ_SIM=false/g" $WEBBASE/web/OJ/include/static.php
    sed -i "s/OJ_SIM_ENABLE=1/OJ_SIM_ENABLE=0/g" /home/judge/etc/judge.conf
else
    sed -i "s/OJ_SIM=false/OJ_SIM=true/g" $WEBBASE/web/OJ/include/static.php
    sed -i "s/OJ_SIM_ENABLE=0/OJ_SIM_ENABLE=1/g" /home/judge/etc/judge.conf
fi
echo ""
echo "7-Please select trun on/off show the contest's solution in status page.(argument:\$OJ_show_contestSolutionInStatus)"
echo "1) Trun on  (contest's solution will be show in status page and contest-status page.)"
echo "2) Trun off (contest's solution will be show in contest-status page only.)"
temp=0
while test $temp != 1 -a $temp != 2
do
    printf "#? "
    read temp
done
if test $temp = 1
then
    sed -i "s/OJ_show_contestSolutionInStatus=false/OJ_show_contestSolutionInStatus=true/g" $WEBBASE/include/static.php
else
    sed -i "s/OJ_show_contestSolutionInStatus=true/OJ_show_contestSolutionInStatus=false/g" $WEBBASE/include/static.php
fi

echo "Install HZNUOJ successfuly!"
echo ""
echo "Remember your database account for HZNUOJ:"
echo "username:$DBUSER"
echo "password:$DBPASS"