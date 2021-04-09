<?php
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
require_once('./include/const.inc.php');
if (isset($_GET['cid'])){

    if (isset($_SESSION['contest_id']) && $_SESSION['contest_id']!=$_GET['cid']) {
        $view_errors = "<font style='color:red;text-decoration:underline;'>You can only enter the correspond contest!</font>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
    }
    
    $cid=intval($_GET['cid']);
    $view_cid=$cid;
    
    // check contest valid
    $sql="SELECT * FROM `contest` WHERE `contest_id`='$cid' ";
    $result=$mysqli->query($sql);
    $rows_cnt=$result->num_rows;
    $contest_ok=true;
    $password="";
    if(isset($_POST['pwd'])) $password=$mysqli->real_escape_string($_POST['pwd']);
    if (get_magic_quotes_gpc ()) {
        $password = stripslashes ($password);
    }
    if ($rows_cnt==0){
        $result->free();
        $view_title= "比赛已经关闭!";
    } else {
        $row=$result->fetch_object();
        if($row->user_limit=="Y" && $_SESSION['contest_id']!=$cid && !HAS_PRI("edit_contest")){
            require_once "template/".$OJ_TEMPLATE."/contest_header.php";
            echo  "<div class='am-text-center'><font style='color:red;text-decoration:underline;'>You are not invited to this contest!</font></div>";
            require_once "template/".$OJ_TEMPLATE."/footer.php";
            exit(0);
        }
        $view_private=$row->private;
        if($password!=""&&$password==$row->password) $_SESSION['c'.$cid]=true;
        if ($row->private && !isset($_SESSION['c'.$cid])) $contest_ok=false;
        if ($row->defunct=='Y') $contest_ok=false;
        if (HAS_PRI("edit_contest")) $contest_ok=true;
        
        if (!$contest_ok){
            $view_errors = "<font style='color:red;text-decoration:underline;'>$MSG_PRIVATE_WARNING</font><br>";
            $view_errors .= "Click <a href=contestrank.php?cid=$cid>HERE</a> to watch contest rank, or input password to enter it.";
            $view_errors .= "<form method=post action='contest.php?cid=$cid' class='am-form-inline am-text-center'>";
            $view_errors .= "<div class='am-form-group'>";
            $view_errors .= "<input class='am-form-field' type='password' name='pwd' placeholder='input contest password'>";
            $view_errors .= "</div>";
            $view_errors .= "<div class='am-form-group'>";
            $view_errors .= "<button class='am-btn am-btn-default' type=submit>submit</button>";
            $view_errors .= "</div>";
            $view_errors .= "</form>";
            require("template/".$OJ_TEMPLATE."/error.php");
            exit(0);
        }
        $now=time();
        $start_time=strtotime($row->start_time);
        $end_time=strtotime($row->end_time);
        $view_description=$row->description;
        $view_title= $row->title;
        $view_start_time=$row->start_time;
        $view_end_time=$row->end_time;
        $practice = $row->practice;
        $can_enter_contest = true;
        if (!HAS_PRI("edit_contest") && $now<$start_time){
            $can_enter_contest = false;
        }
    }

    
    $user_id = $mysqli->real_escape_string($_SESSION['user_id']);
    $contest_id = $mysqli->real_escape_string($_GET['cid']);
    $sql = "select code,in_date,status from printer_code where user_id = '$user_id' and contest_id = '$contest_id'";
    $result = $mysqli->query($sql);
    $printed_codes = [];
    if ($result){
        while ($row=$result->fetch_object()) {
            array_push($printed_codes, $row);
        }
    }
    $result->free();

}
require("template/".$OJ_TEMPLATE."/contest_code_printer.php");
?>