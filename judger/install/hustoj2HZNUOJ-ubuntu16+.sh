#!/bin/bash
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
WEBBASE=/home/judge/src/web/
DBUSER=`cat /etc/mysql/debian.cnf |grep user|head -1|awk  '{print $3}'`
DBPASS=`cat /etc/mysql/debian.cnf |grep password|head -1|awk  '{print $3}'`
echo "Backup hustoj's webpage to /home/judge/webbackup."
mkdir -p /home/judge/webbackup
mv -f $WEBBASE /home/judge/webbackup/
echo "Copy HZNUOJ's web/OJ Dir to hustoj."
cd ../..
cp -R web/OJ/. $WEBBASE
echo "Restore hustoj's upload Dir."
cp -R /home/judge/webbackup/web/upload/ $WEBBASE
chown -R www-data $WEBBASE
sed -i "s/DB_USER=\"root\"/DB_USER=\"$DBUSER\"/g" $WEBBASE/include/static.php
sed -i "s/DB_PASS=\"root\"/DB_PASS=\"$DBPASS\"/g" $WEBBASE/include/static.php
sed -i "s/OJ_HOME=\"\/OJ\/\"/OJ_HOME=\".\/\"/g" $WEBBASE/include/static.php
cd ./judger/install
echo "Update Datebase from hustoj to HZNUOJ, please wait."
mysql -h localhost -u$DBUSER -p$DBPASS < hustoj2HZNUOJ.sql
echo ""
echo "OJ Configuration:"
echo ""
printf "1-Please input OJ's name, press Enter for default name(argument:\$OJ_NAME): "
read ojname
if test "$ojname" != ""
then
    sed -i "s/OJ_NAME=\"HZNUOJ\"/OJ_NAME=\"$ojname\"/g" $WEBBASE/include/static.php
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
    sed -i "s/OJ_LANG=\"en\"/OJ_LANG=\"cn\"/g" $WEBBASE/include/static.php
else
    sed -i "s/OJ_LANG=\"cn\"/OJ_LANG=\"en\"/g" $WEBBASE/include/static.php
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
    sed -i "s/OJ_AUTO_SHARE=true/OJ_AUTO_SHARE=false/g" $WEBBASE/include/static.php
else
    sed -i "s/OJ_AUTO_SHARE=false/OJ_AUTO_SHARE=true/g" $WEBBASE/include/static.php
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
    sed -i "s/OJ_SHOW_DIFF=true/OJ_SHOW_DIFF=false/g" $WEBBASE/include/static.php
else
    sed -i "s/OJ_SHOW_DIFF=false/OJ_SHOW_DIFF=true/g" $WEBBASE/include/static.php
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
    sed -i "s/OJ_SIM=true/OJ_SIM=false/g" $WEBBASE/include/static.php
    sed -i "s/OJ_SIM_ENABLE=1/OJ_SIM_ENABLE=0/g" /home/judge/etc/judge.conf
else
    sed -i "s/OJ_SIM=false/OJ_SIM=true/g" $WEBBASE/include/static.php
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
echo "The update have successfully completed!"
echo ""
echo "Remember your database account for OJ:"
echo "username:$DBUSER"
echo "password:$DBPASS"
