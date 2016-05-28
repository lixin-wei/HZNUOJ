<?php
  /**
   * This file is created
   * by yybird
   * @2016.03.26
   * last modified
   * by yybird
   * @2016.05.26
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
  $id=strval(intval($_GET['id']));
  $sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  $slanguage=$row->language;
  $sresult=$row->result;
  $stime=$row->time;
  $smemory=$row->memory;
  $sproblem_id=$row->problem_id;
  $view_user_id=$suser_id=$row->user_id;
  $cid = $row->contest_id;
  mysql_free_result($result);


  if (isset($OJ_AUTO_SHARE)&&$OJ_AUTO_SHARE&&isset($_SESSION['user_id'])){
    $sql="SELECT 1 FROM solution where 
        result=4 and problem_id=$sproblem_id and user_id='".$_SESSION['user_id']."'";
    $rrs=mysql_query($sql);
    $ok=(mysql_num_rows($rrs)>0);
    mysql_free_result($rrs);
  }

  $view_source="No source code available!";
  if (isset($_SESSION['user_id'])&&$row && $row->user_id==$_SESSION['user_id']) $ok=true; // 是本人，可以查看该代码
  else { // 不是本人的情况下
    if (is_numeric($cid)) { // 该代码是在比赛中的
      $sql = "SELECT defunct_TA, open_source FROM contest WHERE contest_id='$cid'";
      $result = mysql_query($sql);
      $row = mysql_fetch_object($result);
      $open_source = $row->open_source=="N"?0:1; // 默认值为1
      $defunct_TA = $row->defunct_TA=="Y"?1:0; // 默认值为0
      mysql_free_result($result);
      $flag = ( (!is_running(intval($cid)) && $open_source) || // 比赛已经结束了且开放源代码查看
                $GE_T || isset($_SESSION['source_browser']) || // 权限在教师以上或者有看代码权限
                (!$GE_T && $GE_TA && !$defunct_TA) // 是助教且该比赛没屏蔽助教
              );
      if ($flag) $ok = true;
    } else { // 该代码不是在比赛中的
      if ($GE_TA || isset($_SESSION['source_browser'])) $ok = true; // 所有有管理权限的成员均可查看
    }
  }

  $sql="SELECT `source` FROM `source_code` WHERE `solution_id`=".$id;
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  if($row) $view_source=$row->source;

 if ($ok==true) {
    $brush=strtolower($language_name[$slanguage]);
    if ($brush=='pascal') $brush='delphi';
    if ($brush=='obj-c') $brush='c';
    if ($brush=='freebasic') $brush='vb';
    ob_start();
    echo "/**************************************************************\n";
    echo "\tProblem: $sproblem_id\n\tUser: $suser_id\n";
    echo "\tLanguage: ".$language_name[$slanguage]."\n\tResult: ".$judge_result[$sresult]."\n";
    if ($sresult==4){
      echo "\tTime:".$stime." ms\n";
      echo "\tMemory:".$smemory." kb\n";
    }
    echo "****************************************************************/\n\n";
    $auth=ob_get_contents();
    ob_end_clean();

    echo (str_replace("\n\r","\n",$view_source))."\n".$auth;
  } else {
    echo "I am sorry, You could not view this code!";
  }
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>

