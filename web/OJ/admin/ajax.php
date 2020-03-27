<?php
require_once("../include/db_info.inc.php");
$pid=intval($_POST['ppid']);
$sql="SELECT * FROM `problem` WHERE `problem_id`='$pid'";
$result=$mysqli->query($sql);
$row=$result->fetch_object();
if (!HAS_PRI("edit_".$row->problemset."_problem")) {
    echo "error";
    exit(0);
}

if(isset($_POST["m"]) && $_POST["m"]=="problem_add_source"){
    $new_source=$mysqli->real_escape_string(trim($_POST['ns']));
    $category = array_unique(explode(" ",$row->source));//去重
    if(in_array($new_source,$category)){
        echo 0;//有重复，不添加进数据库
    } else {
        echo 1;
        array_push($category,$new_source);
    }
    //$sql="UPDATE `problem` SET `source`=concat(`source`,' ','$new_source') WHERE `problem_id`='$pid'";
    $sql="UPDATE `problem` SET `source`='". implode(' ',$category) ."' WHERE `problem_id`='$pid'";
    $mysqli->query($sql);
}
?>