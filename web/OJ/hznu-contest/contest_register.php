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
$phone=$mysqli->real_escape_string($_POST['phone']);

$sql="INSERT INTO hznu_contest_user (year, institute, stu_id, class, name, register_time, anonymous, phone) VALUES ($year, '$institute', '$stu_id', '$class', '$name', NOW(), $anonymous, '$phone')";
//echo "$sql";
if($mysqli->query($sql)){
	echo "注册成功!";
}
else{
	echo "注册失败! 请检查信息是否填写有误!";
}

?>
