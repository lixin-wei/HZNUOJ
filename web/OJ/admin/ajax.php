<?php
require_once("../include/db_info.inc.php");
require_once("../include/my_func.inc.php");

if(isset($_GET['getUserList'])) {
    $classes=$_POST['classes'];
    $u = array();
    foreach($classes as $class){
        $class=$mysqli->real_escape_string(trim($class));
        $sql="SELECT `user_id` FROM `users` WHERE `class`='$class' ORDER BY `user_id`";
        if($result=$mysqli->query($sql))
            $u = array_merge($u,array_column($mysqli->query($sql)->fetch_all(MYSQLI_ASSOC), 'user_id'));
    }
    echo implode("\n",$u);
} else {

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
    $category = array_unique(explode(" ",trim($row->source)));//去重
    if(in_array($new_source,$category)){
        echo 0;//有重复，不添加进数据库
    } else {
        echo 1;
        array_push($category,$new_source);
    }
    sortByPinYin($category);//按拼音字母对标签进行排序
    //$sql="UPDATE `problem` SET `source`=concat(`source`,' ','$new_source') WHERE `problem_id`='$pid'";
    $sql="UPDATE `problem` SET `source`='". implode(' ',$category) ."' WHERE `problem_id`='$pid'";
    $mysqli->query($sql);
}

}
?>