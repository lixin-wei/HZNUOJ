<?php
require_once "../../include/db_info.inc.php";
if(!session_id()) session_start();
$json = array();
if (HAS_PRI("edit_contest")) {
    $question_id = intval($_POST['id']);
    $reply_content = $mysqli->real_escape_string($_POST['content']);

    $sql = "UPDATE contest_discuss SET reply='$reply_content', reply_date=NOW() WHERE id='$question_id'";

    if($mysqli->query($sql)) {
        $json['result'] = true;
        $json['msg'] = "update done!";
    } else {
        $json['result'] = false;
        $json['msg'] = "database error!";
    }
    echo json_encode($json);
} else {
    $json['result'] = false;
    $json['msg'] = "your have no privilege!";
}