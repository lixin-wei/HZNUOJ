#!/bin/bash
EXTENSION=`echo "$1" | cut -d'.' -f2`
for i in ../data/$2/ac/*.$EXTENSION
do 
	if [ ! -e "/usr/bin/sim_$EXTENSION" ]
	then
		EXTENSION="text";
	fi
	sim=`/usr/bin/sim_$EXTENSION -p $1 $i |grep ^$1|awk '{print $4}'`
	if [ ! -z $sim ] && [ $sim -gt 80 ]
	then 
		sim_s_id=`basename $i`
		echo "$sim $sim_s_id" >sim
		exit $sim 
	fi
done
exit 0;
