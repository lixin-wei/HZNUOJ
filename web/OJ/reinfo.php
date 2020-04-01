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
    exit(0);
  }
  function is_valid($str2){
    return true; // 如果希望能让任何人都查看对比和RE,放开行首注释 if you fail to view diff , try remove the // at beginning of this line.
    $n=strlen($str2);
    $str=str_split($str2);
    $m=1;
    for($i=0;$i<$n;$i++){
      if(is_numeric($str[$i])) $m++;
    }
    return $n/$m>3;
  }

  $id=strval(intval($_GET['sid']));
  $sql="SELECT * FROM `solution` WHERE `solution_id`='".$id."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  if($row->contest_id) $cid = $row->contest_id;
  $ok = can_see_res_info($id);
  if ($ok==true){
    if($row->user_id!=$_SESSION['user_id'])
      $view_mail_link= "<a href='mail.php?to_user=$row->user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
    $sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$id."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_object();
    $see_wa_admin = is_numeric($cid) ? HAS_PRI("see_wa_info_in_contest") : HAS_PRI("see_wa_info_out_of_contest");
    if($row&&($OJ_SHOW_DIFF || $see_wa_admin)&&($OJ_TEST_RUN||is_valid($row->error)|| $see_wa_admin)){
      $view_reinfo= htmlspecialchars(str_replace("\n\r","\n",$row->error));
    }else{
      $view_reinfo="出于数据保密原因，当前错误提示不可查看，如果希望能让任何人都查看对比和运行错误,请管理员编辑本文件，去除相关行的注释，令is_valid总是返回true。 <br>\n Sorry , not available (OJ_SHOW_DIFF:".$OJ_SHOW_DIFF.",TR:".$OJ_TEST_RUN.",valid:".is_valid($row->error).")";
    }
    $result->free();
  } else {
    $result->free();
    $view_errors= "<div style='padding-top: 40px;'>I am sorry, You could not view this message!</div>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }

/////////////////////////Template
require("template/".$OJ_TEMPLATE."/reinfo.php");
/////////////////////////Common foot
//if(file_exists('./include/cache_end.php'))
//  require_once('./include/cache_end.php');
?>

