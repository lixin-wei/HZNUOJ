<?php
require_once "../../include/db_info.inc.php";
if(!session_id()) session_start();
$json = array();
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $cid = intval($_POST['cid']);
    $problem_id = intval($_POST['problem_id']);
    $content = $mysqli->real_escape_string($_POST['content']);

    $sql = "INSERT INTO contest_discuss(user_id, contest_id, problem_id, content, in_date)"
    ." VALUES ('$user_id', '$cid', '$problem_id', '$content', NOW())";

    if($mysqli->query($sql)) {
        $json['result'] = true;
        $json['msg'] = "submit done!";
    } else {
        $json['result'] = false;
        $json['msg'] = "database error!";
    }
    echo json_encode($json);
} else {
    $json['result'] = false;
    $json['msg'] = "please log in first!";
}