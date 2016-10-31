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
  $view_title= $MSG_CONTEST;
  function formatTimeLength($length) {
    $hour = 0;
    $minute = 0;
    $second = 0;
    $result = '';
    
    if ($length >= 60) {
      $second = $length % 60;
      if ($second > 0) {
        $result = $second . '秒';
      }
      $length = floor($length / 60);
      if ($length >= 60) {
        $minute = $length % 60;
        if ($minute == 0) {
          if ($result != '') {
            $result = '0分' . $result;
          }
        } else {
          $result = $minute . '分' . $result;
        }
        $length = floor($length / 60);
        if ($length >= 24) {
          $hour = $length % 24;
          if ($hour == 0) {
            if ($result != '') {
              $result = '0小时' . $result;
            }
          } else {
            $result = $hour . '小时' . $result;
          }
          $length = floor($length / 24);
          $result = $length . '天' . $result;
        } else {
          $result = $length . '小时' . $result;
        }
      } else {
        $result = $length . '分' . $result;
      }
    } else {
      $result = $length . '秒';
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
    if(isset($_POST['password'])) $password=$_POST['password'];
    if (get_magic_quotes_gpc ()) {
      $password = stripslashes ($password);
    }
    if ($rows_cnt==0){
      $result->free();
      $view_title= "比赛已经关闭!";
    } else {
      $row=$result->fetch_object();
      $view_private=$row->private;
      if($password!=""&&$password==$row->password) $_SESSION['c'.$cid]=true;
      if ($row->private && !isset($_SESSION['c'.$cid])) $contest_ok=false;
      if ($row->defunct=='Y') $contest_ok=false;
      if (HAS_PRI("edit_contest")) $contest_ok=true;
                  
      $now=time();
      $start_time=strtotime($row->start_time);
      $end_time=strtotime($row->end_time);
      $view_description=$row->description;
      $view_title= $row->title;
      $view_start_time=$row->start_time;
      $view_end_time=$row->end_time;

      if (!HAS_PRI("edit_contest") && $now<$start_time){
        $view_errors = "<font style='color:red;text-decoration:underline;'>The contest hasn't start yet!</font>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
      }
    }
    if (!$contest_ok){
      $view_errors = "<font style='color:red;text-decoration:underline;'>$MSG_PRIVATE_WARNING</font><br>";
      $view_errors .= "Click <a href=contestrank.php?cid=$cid>HERE</a> to watch contest rank, or input password to enter it.";
      $view_errors .= "<form method=post action='contest.php?cid=$cid' class='am-form-inline am-text-center'>";
      $view_errors .= "<div class='am-form-group'>";
      $view_errors .= "<input class='am-form-field' type='password' name='password' placeholder='input contest password'>";
      $view_errors .= "</div>";
      $view_errors .= "<div class='am-form-group'>";
      $view_errors .= "<button class='am-btn am-btn-default' type=submit>submit</button>";
      $view_errors .= "</div>";
      $view_errors .= "</form>";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
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
      ORDER BY
        pnum
SQL;
    $result=$mysqli->query($sql);
    $view_problemset=Array();
      
    $cnt=0;
    while ($row=$result->fetch_object()){
      $view_problemset[$cnt][0]="";
      if (isset($_SESSION['user_id'])) 
        $view_problemset[$cnt][0]=check_ac($cid,$cnt);
      if ($now>$end_time || HAS_PRI("edit_contest")) // 比赛结束，或者当前用户是管理员则显示 Problem ID
        $view_problemset[$cnt][1]= "<a href='problem.php?id=$row->pid' style='margin:10px;'>$row->pid</a>";
      // $view_problemset[$cnt][1] .= "Problem &nbsp;".(chr($cnt+ord('A')));
      if ($cnt < 26) $pid = chr($cnt+ord('A'));
      else {
        $pid = chr(($cnt/26)-1+ord('A'));
        $pid .= chr($cnt%26+ord('A'));
      }
      $view_problemset[$cnt][1] .= "Problem &nbsp;".$pid;
      $view_problemset[$cnt][2]= "<a href='problem.php?cid=$cid&pid=$cnt'>$row->title</a>";
      $view_problemset[$cnt][3]=$row->author;
      $view_problemset[$cnt][4]=$row->accepted ;
      $view_problemset[$cnt][5]=$row->submit ;
      $cnt++;
    }
    $result->free();
  } else {
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
        $view_contest[$i][2]= "<font color=red><span class=green>$MSG_Ended@$row->end_time</span></font>";
      } else if ($now<$start_time){ // pending
        $view_contest[$i][2]= "<font color=green><span class=blue>$MSG_Start@$row->start_time</span>&nbsp;";
        $view_contest[$i][2].= "<span class=green>$MSG_TotalTime ".formatTimeLength($length)."</span></font>";
      } else { // running
        $view_contest[$i][2]= "<font color=blue><span class=red> $MSG_Running&nbsp;";
        $view_contest[$i][2].= "<span class=green> $MSG_LeftTime ".formatTimeLength($left)." </span></font>";
      }
        $private=intval($row->private);
        if ($private==0) $view_contest[$i][4]= "<span class='am-badge am-badge-success'>$MSG_Public</span>";
        else $view_contest[$i][5]= "<span class='am-badge am-badge-danger'>$MSG_Private</span>";
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
