#!/bin/bash
printf "Upgrade Core? Input 'y' to continue:"
read comfirm
if [["$comfirm" == "y"]]
then
    set -e -x
    pkill -9 judged
    cd ../core/
	bash ./make.sh
    judged
	ps -A | grep judged
else
    printf "Nothing have been changed."
fi
