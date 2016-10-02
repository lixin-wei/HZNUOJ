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

  $id=strval(intval($_GET['sid']));
  $ok = can_see_res_info($id);
  if ($ok==true){
    if($row->user_id!=$_SESSION['user_id'])
      $view_mail_link= "<a href='mail.php?to_user=$row->user_id&title=$MSG_SUBMIT $id'>Mail the auther</a>";
    $sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$id."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_object();
    if($row&&($OJ_SHOW_DIFF||$OJ_TEST_RUN||is_valid($row->error)))  
      $view_reinfo= htmlspecialchars(str_replace("\n\r","\n",$row->error));
    $result->free();
  } else {
    $result->free();
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

