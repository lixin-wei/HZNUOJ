<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php
  $cache_time=30;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  $now=strftime("%Y-%m-%d %H:%M",time());
  if (isset($_GET['cid'])) $ucid="&cid=".intval($_GET['cid']);
  else $ucid="";
  require_once("./include/db_info.inc.php");

  if(isset($OJ_LANG)) require_once("./lang/$OJ_LANG.php");
  /* 获取我的标签 start */
  $my_tag;
  if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $uid = mysql_escape_string($_SESSION['user_id']);
    $id=intval($_GET['id']);
    $sql = "SELECT tag FROM tag WHERE user_id='$uid' AND problem_id='$id'";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);
    $my_tag = $row['tag'];
    mysql_free_result($result);
  }
  /* 获取我的标签 end */
  /* 判断当前用户是否已AC本题 start*/
  $is_solved = false;
  if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $uid = mysql_escape_string($_SESSION['user_id']);
    $id=intval($_GET['id']);
    $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$id' AND result='4'";
    $result = mysql_query($sql);
    if (mysql_num_rows($result)) $is_solved = true;
    mysql_free_result($result);
  }
  /* 判断当前用户是否已AC本题 end*/


  $pr_flag=false;
  $co_flag=false;

  if (isset($_GET['id'])) { // 如果是比赛外的题目
    $id=intval($_GET['id']);
    //require("oj-header.php");
    $res = mysql_query("SELECT problemset from problem WHERE problem_id=$id");
    $set_name = mysql_fetch_array($res)[0];
    $now=strftime("%Y-%m-%d %H:%M",time());
    if (HAS_PRI("see_hidden_".$set_name."_problem")){
      $sql="SELECT * FROM `problem` WHERE `problem_id`=$id";
    }
    else 
      $sql=<<<sql
        SELECT 
          * 
        FROM 
          problem
        WHERE 
          defunct='N' 
          AND problem_id=$id
          AND problem_id
          NOT IN ( 
            SELECT DISTINCT
              contest_problem.problem_id
            FROM
              contest_problem
            JOIN
              contest
            ON
              contest.start_time<='$now' AND contest.end_time>'$now'  #problems that are in runing contest
              AND contest_problem.contest_id=contest.contest_id
          )
sql;
    $pr_flag=true;

  } else if (isset($_GET['cid']) && isset($_GET['pid'])) { // 如果是比赛中的题目


    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);

    if (isset($_SESSION['contest_id']) && $_SESSION['contest_id']!=$_GET['cid']) {
      $view_errors = "<font style='color:red;text-decoration:underline;'>You can only enter the correspond contest!</font>";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }

    

    if (!HAS_PRI("edit_contest"))// if you can edit contest, you can see these problem in passing
      $sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid AND `start_time`<='$now'";
    else
      $sql="SELECT langmask,private,defunct FROM `contest` WHERE `defunct`='N' AND `contest_id`=$cid";
    $result=mysql_query($sql);
    $rows_cnt=mysql_num_rows($result);
    $row=mysql_fetch_row($result);
    
    $contest_ok=true;
    if ($row[1] && !isset($_SESSION['c'.$cid])) $contest_ok=false;
    if ($row[2]=='Y') $contest_ok=false;
    if (HAS_PRI("edit_contest")) $contest_ok=true;

    $ok_cnt=$rows_cnt==1;              
    $langmask=$row[0];
    mysql_free_result($result);
    if ($ok_cnt!=1){
      // not started
      $view_errors=  "No such Contest!";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }else{
      // started
      $sql="SELECT * FROM `problem` WHERE `defunct`='N' AND `problem_id`=(
              SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid)";
    }
    // public
    if (!$contest_ok){
      $view_errors= "Not Invited!";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }
    $co_flag=true;


  } else { // 否则提示找不到该题
    $view_errors=  "<title>$MSG_NO_SUCH_PROBLEM</title><h2>$MSG_NO_SUCH_PROBLEM</h2>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }

  $result=mysql_query($sql) or die(mysql_error());
  if (mysql_num_rows($result)!=1){
    $view_errors="";
    if(isset($_GET['id'])){
      $id=intval($_GET['id']);
      mysql_free_result($result);
      $sql="SELECT  contest.`contest_id` , contest.`title`,contest_problem.num FROM `contest_problem`,`contest` WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=$id and defunct='N'  ORDER BY `num`";
      //echo $sql;
      $result=mysql_query($sql);
      if($i=mysql_num_rows($result)){
         $view_errors.= "This problem is in Contest(s) below:<br>";
         for (;$i>0;$i--){
           $row=mysql_fetch_row($result);
           $view_errors.= "<a href=problem.php?cid=$row[0]&pid=$row[2]>Contest $row[0]:$row[1]</a><br>";
         }
      }else{
        $view_title= "<title>$MSG_NO_SUCH_PROBLEM!</title>";
        $view_errors.= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
      }
    }else{
      $view_title= "<title>$MSG_NO_SUCH_PROBLEM!</title>";
      $view_errors.= "<h2>$MSG_NO_SUCH_PROBLEM!</h2>";
    }
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }else{
    $row=mysql_fetch_object($result);
    $view_title= $row->title;
  }
  mysql_free_result($result);


  /* 获取标签 start */
  $tag = array();
  $sql = "SELECT tag, COUNT(tag) AS sum FROM (SELECT tag FROM tag WHERE problem_id='$id') AS t GROUP BY tag ORDER BY sum DESC LIMIT 10";
  $result = mysql_query($sql);
  for ($i=0; $tag_row=mysql_fetch_array($result); ++$i) {
    $tag[$i] = $tag_row['tag'];
  }
  mysql_free_result($result);
  /* 获取标签 end */


/////////////////////////Template
require("template/".$OJ_TEMPLATE."/problem.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>

