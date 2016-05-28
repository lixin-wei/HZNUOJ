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
  $cache_time=10;
  $OJ_CACHE_SHARE=false;
//  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  require_once("./include/my_func.inc.php");
  $view_title= "Welcome To Online Judge";
  
  require_once("./include/const.inc.php");
  if (!isset($_GET['sid'])){
    echo "No such code!\n";
  //  require_once("oj-footer.php");
    exit(0);
  }
  function is_valid($str2){
    return 1;
    $n=strlen($str2);
    $str=str_split($str2);
    $m=1;
    for($i=0;$i<$n;$i++){
      if(is_numeric($str[$i])) $m++;
    }
    return $n/$m>3;
  }


  /* 判断是否有查看权限 start */
  $ok=false;
  $id=strval(intval($_GET['sid']));
  $sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  if (is_numeric($row->contest_id)) { // 代码是在比赛中的
    $cid = $row->contest_id;
    $sql_tmp = "SELECT defunct_TA, open_source FROM contest WHERE contest_id='$cid'";
    $result_tmp = mysql_query($sql_tmp);
    $row_tmp = mysql_fetch_object($result_tmp);
    $open_source = $row_tmp->open_source=="N"?0:1; // 默认值为1
    $defunct_TA = $row_tmp->defunct_TA=="Y"?1:0; // 默认值为0
    mysql_free_result($result_tmp);
    $flag = ( (!is_running(intval($cid)) && $open_source) || // 比赛已经结束了且开放源代码查看
              $GE_T || isset($_SESSION['source_browser']) || // 权限在教师以上或者有看代码权限
              (!$GE_T && $GE_TA && !$defunct_TA) // 是助教且该比赛没屏蔽助教
            );
    if ($flag) $ok = true;
  } else {
    if ($row && $row->user_id==$_SESSION['user_id']) $ok=true; // 是本人，有查看权限
    if ($GE_TA || isset($_SESSION['source_browser'])) $ok = true; // 所有有管理权限的成员均可查看
  }
  $view_reinfo="";
  /* 判断是否有查看权限 end */



  if ($ok==true){
    if($row->user_id!=$_SESSION['user_id'])
      $view_mail_link= "<a href='mail.php?to_user=$row->user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
    mysql_free_result($result);
    $sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$id."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_object($result);
    if($row&&($OJ_SHOW_DIFF||$OJ_TEST_RUN||is_valid($row->error)))  
      $view_reinfo= htmlspecialchars(str_replace("\n\r","\n",$row->error));
    mysql_free_result($result);
  } else {
    mysql_free_result($result);
    $view_errors= "I am sorry, You could not view this message!";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/reinfo.php");
/////////////////////////Common foot
//if(file_exists('./include/cache_end.php'))
//  require_once('./include/cache_end.php');
?>

