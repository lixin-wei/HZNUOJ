<?php
$folder=上传的文件目录;
$owner = posix_getpwuid(fileowner($folder));
$group = posix_getpwuid(filegroup($folder));
echo 'owner:'.$owner['name'].'<br>';
echo 'group:'.$group['name'].'<br>';
echo 'perms:'.substr(sprintf('%o',fileperms($folder)),-4);
?>
