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
if(isset($_GET['my']) && isset($_SESSION['contest_id'])){ //不允许比赛用户查看“我的比赛、作业”
    $view_errors= "<font color='red'>$MSG_HELP_TeamAccount_forbid</font>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
}
function formatTimeLength($length)
{
  $hour = 0;
  $minute = 0;
  $second = 0;
  $result = '';
  global $OJ_LANG;
  //加个语言判断，cn则显示中文时间，其他的都显示英文
  if($OJ_LANG == "cn"){
	  if($length >= 60){
		$second = $length%60;
		if($second > 0){ $result = $second.'秒';}
		$length = floor($length/60);
		if($length >= 60){
		  $minute = $length%60;
		  if($minute == 0){ if($result != ''){ $result = '0分' . $result;}}
		  else{ $result = $minute.'分'.$result;}
		  $length = floor($length/60);
		  if($length >= 24){
			$hour = $length%24;
			if($hour == 0){ if($result != ''){ $result = '0小时' . $result;}}
			else{ $result = $hour . '小时' . $result;}
			$length = floor($length / 24);
			$result = $length . '天' . $result;
		  } else{ $result = $length . '小时' . $result;}
		} else{ $result = $length . '分' . $result;}
	  } else{ $result = $length . '秒';}
  } else {
	  if($length >= 60){
		$second = $length%60;
		if($second > 0){ $result = $second.' Second'.($second>1?"s":"");}
		$length = floor($length/60);
		if($length >= 60){
		  $minute = $length%60;
		  if($minute == 0){ if($result != ''){ $result = '0 Minute' . $result;}}
		  else{ $result = $minute.' Minute'.($length>1?"s":"")." ".$result;}
		  $length = floor($length/60);
		  if($length >= 24){
			$hour = $length%24;
			if($hour == 0){ if($result != ''){ $result = '0 Hour' . $result;}}
			else{ $result = $hour . ' Hour'.($length>1?"s":"")." " . $result;}
			$length = floor($length / 24);
			$result = $length . ' Day'.($length>1?"s":"")." " . $result;
		  } else{ $result = $length . ' Hour'.($length>1?"s":"")." " . $result;}
		} else{ $result = $length . ' Minute'.($length>1?"s":"")." " . $result;}
	  } else{ $result = $length . ' Second'.($length>1?"s":"");}
  }
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
        $view_title= $MSG_ContestIsClosed;
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
            $view_errors .= "<a href=contestrank.php?cid=$cid>$MSG_WATCH_RANK</a>";
            $view_errors .= "<form method=post action='contest.php?cid=$cid' class='am-form-inline am-text-center'>";
            $view_errors .= "<div class='am-form-group'>";
            $view_errors .= "<input class='am-form-field' type='password' name='pwd' placeholder='$MSG_Input$MSG_PASSWORD'>";
            $view_errors .= "</div>";
            $view_errors .= "<div class='am-form-group'>";
            $view_errors .= "<button class='am-btn am-btn-default' type=submit>$MSG_SUBMIT</button>";
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
    if($can_enter_contest) {
        
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
            num AS pnum,
            contest_problem.score as score
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
        $view_problemset[$cnt][1] = $row->score;
        if ($practice || $now>$end_time || HAS_PRI("edit_contest")) // 比赛结束，或者是practice，或者当前用户是管理员则显示 Problem ID
            $view_problemset[$cnt][2]= "<a href='problem.php?id=$row->pid' style='margin:10px;'>$row->pid</a>";
        $view_problemset[$cnt][2] .= "$MSG_PROBLEM &nbsp;".PID($row->pnum);
        if($practice && is_in_running_contest($row->pid) && !$can_edit_contest)
            $view_problemset[$cnt][3]= "<span style='color: dimgrey;' title='this problem is locked because they are in running contest.'>$row->title <i class='am-icon-lock'></i></span>";
        else
            $view_problemset[$cnt][3]= "<a href='problem.php?cid=$cid&pid=$row->pnum'>$row->title</a>";
        $view_problemset[$cnt][4]=$row->author;
        $view_problemset[$cnt][5]=$row->accepted ;
        $view_problemset[$cnt][6]=$row->submit ;
        if($practice) {
            $view_problemset[$cnt][7]=$row->total_accepted ;
            $view_problemset[$cnt][8]=$row->total_submit ;
        }
        $cnt++;
    }
    $result->free();
} else {
  $getMy = "";
  $sql_filter = "";
  $mycontests = "";	
  if(isset($_GET['my'])){ //我的比赛、作业
    $getMy = "my";
    foreach($_SESSION as $key => $value){
      if(($key[0]=='m'||$key[0]=='c')&&intval(mb_substr($key,1))>0){ //验证是否有m1、c1之类的
        $mycontests.=",".intval(mb_substr($key,1));
      }
    }
    if($mycontests) $sql_filter .=" AND contest_id IN (".substr($mycontests,1).")";//去掉最开始的","
    else $sql_filter .=" AND 0 ";
  }
  if(isset($_GET['search'])&&trim($_GET['search'])!="") {
    $search=$mysqli->real_escape_string(trim($_GET['search']));
    $sql_filter .= "AND contest.title LIKE '%$search%'";
  }
  if(isset($_GET['type']) && trim($_GET['type']) != "" && trim($_GET['type']) != "all") {
    switch (trim($_GET['type'])) {
      case "Special":
        $sql_filter .= " AND (NOT `practice` AND `user_limit`='Y') ";
      break;
      case "Private":
        $sql_filter .= " AND (NOT `practice` AND `user_limit`='N' AND `private`) ";
      break;
      case "Public":
        $sql_filter .= " AND (NOT `practice` AND `user_limit`='N' AND NOT `private`) ";
      break;
      case "Practice":
        $sql_filter .= "AND `practice` ";
      break;
    }
  }
  if(isset($_GET['runstatus']) && trim($_GET['runstatus']) != "" && trim($_GET['runstatus']) != "all") {
    switch (trim($_GET['runstatus'])) {
      case "noStart":
        $sql_filter .= " AND `start_time`>NOW() ";
      break;
      case "Running":
        $sql_filter .= " AND (`start_time`<NOW() AND `end_time`>NOW()) ";
      break;
      case "Ended":
        $sql_filter .= " AND `end_time`<NOW() ";
      break;
    }
  }
    $page = 1;
    if(isset($_GET['page'])) $page = intval($_GET['page']);
    $page_cnt = 10;
    $sql0 = "SELECT count(1) FROM contest WHERE contest.defunct='N' ".$sql_filter;
    //echo $sql0;
    $rows =$mysqli->query($sql0)->fetch_all(MYSQLI_BOTH);
    if($rows) $total = $rows[0][0];
    $view_total_page = intval($total/$page_cnt)+($total%$page_cnt?1:0);//计算页数
    $view_total_page = $view_total_page>0?$view_total_page:1;
    if ($page > $view_total_page) $page = $view_total_page;
    if ($page < 1) $page = 1;
    $pstart = $page_cnt*$page-$page_cnt;
    $pend = $page_cnt;
    $sql = "SELECT *  FROM contest WHERE contest.defunct='N' ".$sql_filter." ORDER BY isTop DESC, contest_id DESC";
    $sql .= " limit ".strval($pstart).",".strval($pend); 
    $result=$mysqli->query($sql);
    $view_contest=Array();
    $i=0;
    while ($row=$result->fetch_object()){
        $view_contest[$i][0]= $row->contest_id;
        if(trim($row->title)=="") $row->title=$MSG_CONTEST.$row->contest_id;
        $view_contest[$i][1]= "<a href='contest.php?cid=$row->contest_id'>$row->title</a>";
        if($row->isTop) $view_contest[$i][1].="<span title='$MSG_Top'>&nbsp;<i class='am-icon-arrow-up'></i>";
        $start_time=strtotime($row->start_time);
        $end_time=strtotime($row->end_time);
        $now=time();
        $length=$end_time-$start_time;
        $left=$end_time-$now;
        
        if ($now>$end_time) { // past
            $view_contest[$i][2]= "<span style='color: #9e9e9e;'>$MSG_Ended@".date('Y-m-d H:i',$end_time)."</span>";
        } else if ($now<$start_time){ // pending
            $view_contest[$i][2]= "<span style='color: #03a9f4;'>$MSG_Start@".date('Y-m-d H:i',$start_time)."&nbsp;";
            $view_contest[$i][2].= "$MSG_TotalTime ".formatTimeLength($length)."</span>";
        } else { // running
            $view_contest[$i][2]= "<span style='color: #ff5722;'> $MSG_Running&nbsp;";
            $view_contest[$i][2].= "$MSG_LeftTime ".formatTimeLength($left)." </span>";
        }
        $type = "<span style='color: green;'>$MSG_Public</span>";
        if($row->private) $type = "<span style='color: dodgerblue;'>$MSG_Private</span>";
        if($row->user_limit=="Y") $type = "<span style='color: #f44336;'>$MSG_Special</span>";
        if($row->practice) $type = "<span style='color: #009688;'>$MSG_Practice</span>";
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
