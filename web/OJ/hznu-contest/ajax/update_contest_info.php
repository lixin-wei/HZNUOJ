<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/config.php";

$title = $mysqli->real_escape_string($_POST['title']);
$announcement = $mysqli->real_escape_string($_POST['announcement']);
$password = $_POST['password'];

$res = array();
$res['result'] = true;
if ($password === $contest_password) {
    $sql = "UPDATE formal_contest SET title='$title', announcement='$announcement' WHERE id='$contest_id'";
    if($mysqli->query($sql)) {
        $res['msg'] = "更新成功";
    }
    else {
        $res['result'] = false;
        $res['msg'] = $mysqli->error;
    }
}
else {
    $res['result'] = false;
    $res['msg'] = "密码错误";
}
echo json_encode($res);