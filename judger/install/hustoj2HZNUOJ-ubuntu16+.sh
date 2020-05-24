#!/bin/bash
printf "Update for http://www.kidsccshow.com/ input 'y', else input 'n':"
read hzqsn
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
 if test $hzqsn = "y"
 then
    # hzqsn config start
    sed -i "s/OJ_NAME=\"HZNUOJ\"/OJ_NAME=\"hzqsnOJ\"/g" $WEBBASE/include/static.php
    sed -i "s/OJ_BEIAN=\"\"/OJ_BEIAN=\"?ICP?17029265?-1\"/g" $WEBBASE/include/static.php
    sed -i "s/OJ_LANG=\"en\"/OJ_LANG=\"cn\"/g" $WEBBASE/include/static.php
    sed -i "s/OJ_AUTO_SHARE=true/OJ_AUTO_SHARE=false/g" $WEBBASE/include/static.php
    sed -i "s/OJ_SHOW_DIFF=true/OJ_SHOW_DIFF=false/g" $WEBBASE/include/static.php
    sed -i "s/OJ_LANGMASK=717823/OJ_LANGMASK=524359/g" $WEBBASE/include/static.php
    sed -i "s/OJ_SIM=false/OJ_SIM=true/g" $WEBBASE/include/static.php    
    sed -i "s/OJ_SIM_ENABLE=0/OJ_SIM_ENABLE=1/g" /home/judge/etc/judge.conf
    sed -i "s/OJ_OI_MODE=0/OJ_OI_MODE=1/g" /home/judge/etc/judge.conf
    sed -i "133d" $WEBBASE/template/hznu/contest_header.php
    sed -i "132d" $WEBBASE/template/hznu/contest_header.php
    sed -i "105d" $WEBBASE/template/hznu/index.php
    sed -i "104d" $WEBBASE/template/hznu/index.php
    sed -i "103d" $WEBBASE/template/hznu/index.php
    sed -i "102d" $WEBBASE/template/hznu/index.php
    sed -i "101d" $WEBBASE/template/hznu/index.php
    sed -i "100d" $WEBBASE/template/hznu/index.php
    sed -i "38d" $WEBBASE/template/hznu/index.php
    sed -i "37d" $WEBBASE/template/hznu/index.php
    sed -i "36d" $WEBBASE/template/hznu/index.php
    sed -i "s/\$sql=\" WHERE contest_id is null \";/\/\/\$sql=\" WHERE contest_id is null \";/g" $WEBBASE/status.php
    sed -i "s/\/\/\$sql=\" WHERE 1 \";/\$sql=\" WHERE 1 \";/g" $WEBBASE/status.php
    mysql -h localhost -u$DBUSER -p$DBPASS -e "UPDATE jol.privilege_distribution SET inner_function=1 WHERE group_name='administrator';"
    mysql -h localhost -u$DBUSER -p$DBPASS -e "UPDATE jol.problem SET source=REPLACE(source, ',', ' ');"
    mysql -h localhost -u$DBUSER -p$DBPASS -e "UPDATE jol.problem SET source=REPLACE(source, '，', ' ');"
    # hzqsn config end
fi
echo "The update have successfully completed!"
echo "Remember your database account for OJ:"
echo "username:$DBUSER"
echo "password:$DBPASS"
