<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.06.27
  **/
?>
<?php
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

  $ok=false;
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
  if (is_numeric($cid)) $p_lable=PID($num);
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

  $ok = canSeeSource($sid);
  $view_source="No source code available!";
  $sql="SELECT `source` FROM `source_code_user` WHERE `solution_id`='$sid'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  if($row) $view_source=$row->source;

 if ($ok==true) {
    if(isset($_GET['ce'])){
      echo "<pre class=\"brush:".$language_brush[$slanguage].";\">";
      echo htmlentities(str_replace("\n\r","\n",$view_source),ENT_QUOTES,"utf-8");
    } else {
      echo str_replace("\n\r","\n",$view_source);
    }
    echo "\n/**************************************************************\n";
    $nick = $is_temp_user ? $tuser_nick : $user_nick;
    $nick = $nick ? "($nick)" : "";
    $ptitle = $ptitle ? "($ptitle)" : "";
    echo "\tProblem: ". ($p_lable?$p_lable:$pid) ." $ptitle\n\tUser: $suser_id $nick\n";
    echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
    echo "\tDate:".$sindate."\n";
    if ($sresult==4){
        echo "\tTime:".$stime." ms\n";
        echo "\tMemory:".$smemory." KB\n";
    }
    echo "****************************************************************/\n";
    if(isset($_GET['ce'])) echo "</pre>";
  } else {
    echo "I am sorry, You could not view this code!";
  }
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>