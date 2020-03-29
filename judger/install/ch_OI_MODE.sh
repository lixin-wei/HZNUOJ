#!/bin/bash
printf "Input OJ Run Mode(OI mode input 1, ACM mode input 0):"
read mode
pkill -9 judged
if test $[mode] -eq 0
then
    sed -i "s/OJ_OI_MODE=1/OJ_OI_MODE=0/g" /home/judge/etc/judge.conf
else if test $[mode] -eq 1
    then
        sed -i "s/OJ_OI_MODE=0/OJ_OI_MODE=1/g" /home/judge/etc/judge.conf
    fi
fi
if test $[mode] -ne 0 && test $[mode] -ne 1
then
    printf "Please input 0 or 1 !\n"
else
    judged
    printf "Well Done! OJ_OI_MODE=$[mode] \n"
fi
