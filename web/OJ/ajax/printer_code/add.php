<?php
require_once "../../include/db_info.inc.php";
if(!session_id()) session_start();
$json = array();
if(isset($_SESSION['user_id'])){
    $user_id=$mysqli->real_escape_string($_SESSION['user_id']);
    $contest_id=$mysqli->real_escape_string($_POST['cid']);
    $code=$mysqli->real_escape_string($_POST ['code']);
    $sql = "INSERT into `printer_code` (`user_id`,`contest_id`,`code`,`in_date`) VALUES('$user_id','$contest_id','$code',NOW())";
    $res = $mysqli->query($sql);
    if ($res) {
        $json['result'] = true;
        $json['msg'] = "successfully submited!";
    } else {
        $json['result'] = false;
        $json['msg'] = "internal error!";
    }
} else {
    $json['result'] = false;
    $json['msg'] = "please log in first!";
}

echo json_encode($json);
?>