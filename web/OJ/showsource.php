<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.26
  **/
?>


<?php
  $title = "Show Source Code";
  $cache_time=90;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  require_once("./include/my_func.inc.php");
  $view_title= "Source Code"; 
  require_once("./include/const.inc.php");
  if (!isset($_GET['id'])){
    $view_errors= "No such code!\n";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
  /* 获取solution信息 start */
  $sid=strval(intval($_GET['id']));
  $sql="SELECT s.*,c.`title` AS ctitle, p.`title` AS ptitle,u.`nick` AS unick, t.`nick` AS tnick FROM `solution` AS s 
  LEFT JOIN `contest` AS c ON s.`contest_id`=c.`contest_id` 
  LEFT JOIN `problem` AS p ON s.`problem_id`=p.`problem_id` 
  LEFT JOIN `users` AS u ON s.`user_id`=u.`user_id` 
  LEFT JOIN `team` AS t ON s.`user_id`=t.`user_id` 
  WHERE s.`solution_id`='".$sid."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  $slanguage=$row->language;
  $sresult=$row->result;
  $stime=$row->time;
  $smemory=$row->memory;
  $view_user_id=$suser_id=$row->user_id;
  $pid = $row->problem_id;
  $cid = $row->contest_id;
  $ctitle = $row->ctitle;
  $ptitle = $row->ptitle;
  $user_nick = $row->unick;
  $tuser_nick = $row->tnick;
  $sindate=$row->in_date;
  $num = $row->num;
  $result->free();
  $is_temp_user = false;
  if($cid) {
      $sql = "SELECT COUNT(1) FROM team WHERE contest_id='$cid' AND user_id='$suser_id'";
      $is_temp_user = $mysqli->query($sql)->fetch_array()[0];
  }

  /* 获取solution信息 end */

  $ok = canSeeSource($sid);

  $view_source="No source code available!";
  
  $sql="SELECT `source` FROM `source_code_user` WHERE `solution_id`='$sid'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  if($row) $view_source=$row->source;

  /////////////////////////Template
  require("template/".$OJ_TEMPLATE."/showsource.php");
  /////////////////////////Common foot
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

