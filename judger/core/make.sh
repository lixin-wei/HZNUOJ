#!/bin/bash
cd judged
make
chmod +x judged
cp judged /usr/bin
cd ../judge_client
make
chmod +x judge_client
cp judge_client /usr/bin
cd ../sim/sim_2_77
make fresh
make exes
chmod +x sim*
cp sim_c.exe /usr/bin/sim_c
cp sim_java.exe /usr/bin/sim_java
cp sim_pasc.exe /usr/bin/sim_pas
cp sim_text.exe /usr/bin/sim_text
cp sim_lisp.exe /usr/bin/sim_scm
cd ..
cp sim.sh /usr/bin
chmod +x /usr/bin/sim.sh
rm /usr/bin/sim_cc /usr/bin/sim_rb /usr/bin/sim_sh
ln -s /usr/bin/sim_c /usr/bin/sim_cc
