<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
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
    if (!HAS_PRI("see_hidden_".get_problemset($id)."_problem")) { // 不够权限看隐藏题
      //判断是否是开放题
      $sql_tmp = "
SELECT problem_id FROM `problem`
WHERE
  `problem_id`=$id
  AND `defunct`='N'
  AND `problem_id` NOT IN (
    SELECT `problem_id` FROM `contest_problem`
    WHERE
      `contest_id` IN(
        SELECT `contest_id` FROM `contest`
        WHERE
          `end_time`>NOW()
          AND start_time <NOW()
          AND practice = 0
      )
  )";
      $result_tmp = $mysqli->query($sql_tmp);
      //echo $result_tmp;
      if ($result_tmp->num_rows != 1) {
        $view_errors = "<span class='am-text-danger'>Problem not available!</span>";
        require("template/".$OJ_TEMPLATE."/error.php");
        exit(0);
      }
      $result_tmp->free();
    }
    /* 判断该用户是否有查看该题目权限 end */

    $sample_sql="select sample_input,sample_output,problem_id from problem where problem_id=$id";


  } else if (isset($_GET['cid'])&&isset($_GET['pid'])) { // 如果提交的是比赛中的题目


    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);

    /* 获取该场比赛是否对用户有限制 start */
    $sql_tmp = "SELECT user_limit,langmask FROM contest WHERE contest_id='$cid'";
    $result_tmp = $mysqli->query($sql_tmp);
    $row_tmp = $result_tmp->fetch_object();
    $user_limit = $row_tmp->user_limit=="Y"?1:0;
    $contest_langmask = $row_tmp->langmask;
    $result_tmp->free();
    /* 获取该场比赛是否对用户有限制 end */

    /* 判断是否有错误 start */
    $error_flag = false;
    if ($user_limit && !isset($_SESSION['contest_id'])) { // 如果不是队伍账号，则退出
      $view_errors = "<font style='color:red;text-decoration:underline;'>You should use team account !</font>";
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
  $ok = canSeeSource($sid);
  if ($ok==true){
    $sql="SELECT `source` FROM `source_code_user` WHERE `solution_id`='".$sid."'";
    $result=$mysqli->query($sql);
    $row=$result->fetch_object();
    if($row)
      $view_src=$row->source;
    $result->free();
  }
  
 }
$problem_id=$id;
$view_sample_input="1 2";
$view_sample_output="3";
 if(isset($sample_sql)){
   //echo $sample_sql;
  $result=$mysqli->query($sample_sql);
  $row=$result->fetch_array();
  $view_sample_input=$row[0];
  $view_sample_output=$row[1];
  $problem_id=$row[2];
  $result->free();
  
  
 }
 
if(!$view_src){
  if(isset($_COOKIE['lastlang'])) 
    $lastlang=intval($_COOKIE['lastlang']);
  else 
    $lastlang=0;
    $template_file="$OJ_DATA/$problem_id/template.".$language_ext[$lastlang];
   if(file_exists($template_file)){
     //上传文件的编码不一定是UTF-8，此时包含中文的情况下htmlentities($view_src)就显示不了内容，因此先把文件内容编码转为UTF-8并返写
     require_once("./include/problem.php");
     $view_src=convert2UTF8($OJ_DATA,$problem_id,pathinfo($template_file)['basename']);
   }
}


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/submitpage.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
?>

