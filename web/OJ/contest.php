<?php
/**
 * This file is modified
 * by yybird
 * @2016.05.24
 **/
?>

<?php
$OJ_CACHE_SHARE=!isset($_GET['cid']);
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/my_func.inc.php');
require_once('./include/setlang.php');
require_once './include/const.inc.php';
$view_title= $MSG_CONTEST;
function formatTimeLength($length) {
    $result = "";
    $day = floor($length/86400); $length%=86400;
    $hour = floor($length/3600); $length%=3600;
    $minute = floor($length/60); $length%=60;
    $second = $length;
    $result .= $day." Day".($day>1?"s":"")." ";
    $result .= $hour." Hour".($hour>1?"s":"")." ";
    $result .= $minute." Minute".($minute>1?"s":"")." ";
    $result .= $second." Second".($second>1?"s":"")." ";
    return $result;
}

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
            require_once "template/hznu/contest_header.php";
            echo  "<div class='am-text-center'><font style='color:red;text-decoration:underline;'>You are not invited to this contest!</font></div>";
            require_once "template/hznu/footer.php";
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
        if (!HAS_PRI("edit_contest") && $now<$start_time){
            require_once "template/hznu/contest_header.php";
            require("template/".$OJ_TEMPLATE."/footer.php");
            exit(0);
        }
    }
    $sql=<<<SQL
      SELECT
        *
      FROM
        (
          SELECT
            `problem`.`title` AS `title`,
            `problem`.`problem_id` AS `pid`,
            source AS source,
            author AS author,
            num AS pnum
          FROM
            `contest_problem`,
            `problem`
          WHERE
            `contest_problem`.`problem_id` = `problem`.`problem_id`
          AND `contest_problem`.`contest_id` = $cid
          ORDER BY
            `contest_problem`.`num`
        ) problem
      LEFT JOIN (
        SELECT
          problem_id pid1,
          Count(DISTINCT user_id) accepted
        FROM
          solution
        WHERE
          result = 4
        AND contest_id = $cid
        GROUP BY
          pid1
      ) p1 ON problem.pid = p1.pid1
      LEFT JOIN (
        SELECT
          problem_id pid2,
          Count(1) submit
        FROM
          solution
        WHERE
          contest_id = $cid
        GROUP BY
          pid2
      ) p2 ON problem.pid = p2.pid2
      LEFT JOIN (
        SELECT
          problem_id pid3,
          Count(DISTINCT user_id) total_accepted
        FROM
          solution
        WHERE
          result = 4
        GROUP BY
          pid3
      ) p3 ON problem.pid = p3.pid3
      LEFT JOIN (
        SELECT
          problem_id pid4,
          Count(1) total_submit
        FROM
          solution
        GROUP BY
          pid4
      ) p4 ON problem.pid = p4.pid4
      ORDER BY
        pnum
SQL;
    $result=$mysqli->query($sql);
    $view_problemset=Array();
    
    $cnt=0;
    $can_edit_contest = HAS_PRI("edit_contest");
    while ($row=$result->fetch_object()){
        $view_problemset[$cnt][0]="";
        if (isset($_SESSION['user_id']))
            $view_problemset[$cnt][0]=check_ac($cid,$cnt);
        if ($practice || $now>$end_time || HAS_PRI("edit_contest")) // 比赛结束，或者是practice，或者当前用户是管理员则显示 Problem ID
            $view_problemset[$cnt][1]= "<a href='problem.php?id=$row->pid' style='margin:10px;'>$row->pid</a>";
        $view_problemset[$cnt][1] .= "Problem &nbsp;".PID($cnt);
        if($practice && is_in_running_contest($row->pid) && !$can_edit_contest)
            $view_problemset[$cnt][2]= "<span style='color: dimgrey;' title='this problem is locked because they are in running contest.'>$row->title <i class='am-icon-lock'></i></span>";
        else
            $view_problemset[$cnt][2]= "<a href='problem.php?cid=$cid&pid=$cnt'>$row->title</a>";
        $view_problemset[$cnt][3]=$row->author;
        $view_problemset[$cnt][4]=$row->accepted ;
        $view_problemset[$cnt][5]=$row->submit ;
        if($practice) {
            $view_problemset[$cnt][6]=$row->total_accepted ;
            $view_problemset[$cnt][7]=$row->total_submit ;
        }
        $cnt++;
    }
    $result->free();
}
else {
    $keyword="";
    if(isset($_POST['keyword'])){
        $keyword=$mysqli->real_escape_string($_POST['keyword']);
    }
    $sql="SELECT * FROM `contest` WHERE `defunct`='N' ORDER BY `contest_id` DESC limit 1000";
    // $sql="select * from contest left join (select * from privilege where rightstr like 'm%') p on concat('m',contest_id)=rightstr where contest.defunct='N' and contest.title like '%$keyword%'  order by contest_id desc limit 1000;";
    $result=$mysqli->query($sql);
    $view_contest=Array();
    $i=0;
    while ($row=$result->fetch_object()){
        $view_contest[$i][0]= $row->contest_id;
        $view_contest[$i][1]= "<a href='contest.php?cid=$row->contest_id'>$row->title</a>";
        $start_time=strtotime($row->start_time);
        $end_time=strtotime($row->end_time);
        $now=time();
        $length=$end_time-$start_time;
        $left=$end_time-$now;
        
        if ($now>$end_time) { // past
            $view_contest[$i][2]= "<span style='color: #9e9e9e;'>$MSG_Ended@$row->end_time</span>";
        } else if ($now<$start_time){ // pending
            $view_contest[$i][2]= "<span style='color: #03a9f4;'>$MSG_Start@$row->start_time&nbsp;";
            $view_contest[$i][2].= "$MSG_TotalTime ".formatTimeLength($length)."</span>";
        } else { // running
            $view_contest[$i][2]= "<span style='color: #ff5722;'> $MSG_Running&nbsp;";
            $view_contest[$i][2].= "$MSG_LeftTime ".formatTimeLength($left)." </span>";
        }
        $type = "<span style='color: green;'>Public</span>";
        if($row->private) $type = "<span style='color: dodgerblue;'>Password</span>";
        if($row->user_limit=="Y") $type = "<span style='color: #f44336;'>Special</span>";
        if($row->practice) $type = "<span style='color: #009688;'>Practice</span>";
        $view_contest[$i][4]= $type;
        $view_contest[$i][6]=$row->user_id;
        $i++;
    }
    $result->free();
}


/////////////////////////Template
if(isset($_GET['cid']))
    require("template/".$OJ_TEMPLATE."/contest.php");
else
    require("template/".$OJ_TEMPLATE."/contestset.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
