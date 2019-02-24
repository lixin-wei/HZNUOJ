<?php
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/include/db_info.inc.php";
require_once $_SERVER['DOCUMENT_ROOT']."/OJ/hznu-contest/config.php";
if($is_end) {
    echo "报名已结束";
    exit(0);
}

$return_msg = "";
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $team_name = $mysqli->real_escape_string($_POST['team_name']);
    $school = $mysqli->real_escape_string($_POST['school']);
    $name1 = $mysqli->real_escape_string($_POST['name1']);
    $name2 = $mysqli->real_escape_string($_POST['name2']);
    $name3 = $mysqli->real_escape_string($_POST['name3']);
    $anonymous = intval($_POST['anonymous']);
    $phone = $mysqli->real_escape_string($_POST['phone']);

    $sql = "SELECT COUNT(*) from formal_contest_user WHERE user_id='$user_id' AND contest_id=$contest_id";
    $has_record = $mysqli->query($sql)->fetch_array()[0];
    if (!$has_record) {
        $sql = "INSERT INTO formal_contest_user (user_id, contest_id, team_name, school, name1, name2, name3, register_time, anonymous, phone) VALUES ('$user_id', '$contest_id', '$team_name', '$school', '$name1', '$name2', '$name3', NOW(), $anonymous, '$phone')";
    }
    else {
        $sql = "UPDATE formal_contest_user SET team_name='$team_name', school='$school', name1='$name1', name2='$name2', name3='$name3', anonymous=$anonymous, phone='$phone' WHERE user_id='$user_id' AND contest_id='$contest_id'";
    }

    //echo "$sql";
    if($mysqli->query($sql)){
        $return_msg = "信息更新成功!";
    }
    else{
        $return_msg = $mysqli->error;
        //$return_msg = "信息更新失败! 请检查信息是否填写有误!";
    }
}
else {
    $return_msg = "请先登录！";
}

echo $return_msg;
?>
