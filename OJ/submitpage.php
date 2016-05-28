<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php
  $cache_time=1;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/const.inc.php');
  require_once("./include/my_func.inc.php");
  require_once('./include/setlang.php');
  $view_title=$MSG_SUBMIT;


  if (!isset($_SESSION['user_id'])){
    $view_errors= "<a href=loginpage.php style='color:red;text-decoration:underline;'>$MSG_Login</a>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }


  if (isset($_GET['id'])) { // 如果是提交的是普通题目


    $id=intval($_GET['id']);
    $uid = $_SESSION['user_id'];
    
    /* 判断该用户是否有查看该题目权限 start */
    if (isset($_SESSION['contest_id'])) { // 如果是临时账号
      $view_errors = "<font style='color:red;text-decoration:underline;'>You can't submit this problem by team account!</font>";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }
    if (!$GE_TA || (!$GE_T&&$GE_TA&&$_GET['id']<=$BORDER)) { // 没有管理权限或只有助教权限但是题目不在C语言区
      $sql_tmp = "SELECT * FROM `problem` WHERE `problem_id`=$id AND `defunct`='N' AND `problem_id` NOT IN (
                      SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN(
                                      SELECT `contest_id` FROM `contest` WHERE `end_time`>'$now' or `private`='1'))";
      $result_tmp = mysql_query($sql_tmp);
      if (mysql_num_rows($result_tmp) != 1) {
        $view_errors = "<font style='color:red;text-decoration:underline;'>Problem not available!</font>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
      }
      mysql_free_result($result_tmp);
    }
    /* 判断该用户是否有查看该题目权限 end */

    $sample_sql="select sample_input,sample_output,problem_id from problem where problem_id=$id";


  } else if (isset($_GET['cid'])&&isset($_GET['pid'])) { // 如果提交的是比赛中的题目


    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);

    /* 获取该场比赛是否对用户有限制 start */
    $sql_tmp = "SELECT user_limit FROM contest WHERE contest_id='$cid'";
    $result_tmp = mysql_query($sql_tmp);
    $row_tmp = mysql_fetch_object($result_tmp);
    $user_limit = $row_tmp->user_limit=="Y"?1:0;
    mysql_free_result($result_tmp);
    /* 获取该场比赛是否对用户有限制 end */

    /* 判断是否有错误 start */
    $error_flag = false;
    if ($user_limit && !isset($_SESSION['contest_id'])) { // 如果不是队伍账号，则退出
      $view_errors = "<font style='color:red;text-decoration:underline;'>You should user team account !</font>";
      $error_flag = true;
    }
    if (isset($_SESSION['contest_id']) && $_SESSION['contest_id']!=$_GET['cid']) { // 如果是队伍账号但没进入正确的比赛中
      $view_errors = "<font style='color:red;text-decoration:underline;'>You can only enter the correspond contest!</font>";
      $error_flag = true; 
    }
    if ($error_flag) {
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }
    /* 判断是否有错误 end */
    
    $sample_sql="select sample_input,sample_output,problem_id from problem where problem_id in (select problem_id from contest_problem where contest_id=$cid and num=$pid)";
       

  } else {
    $view_errors=  "<h2>No Such Problem!</h2>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }


 $view_src="";
 if(isset($_GET['sid'])){
  $sid=intval($_GET['sid']);
  $sql="SELECT * FROM `solution` WHERE `solution_id`=".$sid;
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  if ($row && $row->user_id==$_SESSION['user_id']) $ok=true; // 是本人，可以使用该代码
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
  mysql_free_result($result);
  if ($ok==true){
    $sql="SELECT `source` FROM `source_code_user` WHERE `solution_id`='".$sid."'";
    $result=mysql_query($sql);
    $row=mysql_fetch_object($result);
    if($row)
      $view_src=$row->source;
    mysql_free_result($result);
  }
  
 }
$problem_id=$id;
$view_sample_input="1 2";
$view_sample_output="3";
 if(isset($sample_sql)){
   //echo $sample_sql;
  $result=mysql_query($sample_sql);
  $row=mysql_fetch_array($result);
  $view_sample_input=$row[0];
  $view_sample_output=$row[1];
  $problem_id=$row[2];
  mysql_free_result($result);
  
  
 }
 
if(!$view_src){
  if(isset($_COOKIE['lastlang'])) 
    $lastlang=intval($_COOKIE['lastlang']);
  else 
    $lastlang=0;
   $template_file="$OJ_DATA/$problem_id/template.".$language_ext[$lastlang];
   if(file_exists($template_file)){
  $view_src=file_get_contents($template_file);
   }

}


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/submitpage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>

