
CALLS=""
for x in `grep CALLID\: /home/judge/log/client.log|awk -F: '{print $4}'|awk '{print $1}'|sort -nu `
do
     CALLS="$CALLS,$x"
done
echo "int LANG_CV[CALL_ARRAY_SIZE]={0$CALLS,0};"
