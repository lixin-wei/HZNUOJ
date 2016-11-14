<?php
require_once "../include/db_info.inc.php";

$institute=$mysqli->real_escape_string($_POST['institute']);
$stu_id=$mysqli->real_escape_string($_POST['stu_id']);
$class=$mysqli->real_escape_string($_POST['class']);
$name=$mysqli->real_escape_string($_POST['name']);
$anonymous=$_POST['anonymous'];

$sql="SELECT name FROM contest_hznu_2016 WHERE institute='$institute' AND stu_id='$stu_id' AND class='$class' AND name='$name'";
$res=$mysqli->query($sql);
if($res->num_rows>0) echo "恭喜，此账号已成功注册!";
else echo "此账号尚未注册";
?>