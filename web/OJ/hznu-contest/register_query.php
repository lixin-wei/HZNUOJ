<?php
require_once "../include/db_info.inc.php";
require_once "./config.php";
if($isEnd) {
    echo "报名已结束";
    exit(0);
}

$institute=$mysqli->real_escape_string($_POST['institute']);
$stu_id=$mysqli->real_escape_string($_POST['stu_id']);
$class=$mysqli->real_escape_string($_POST['class']);
$name=$mysqli->real_escape_string($_POST['name']);
$anonymous=$_POST['anonymous'];

$sql="SELECT name FROM hznu_contest_user WHERE institute='$institute' AND stu_id='$stu_id' AND class='$class' AND name='$name' AND year = $year";
$res=$mysqli->query($sql);
if($res->num_rows>0) echo "恭喜，此账号已成功注册!";
else echo "此账号尚未注册";
?>