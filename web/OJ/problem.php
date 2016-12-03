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
  if(isset($_SESSION['user_id'])){
    $uid = $mysqli->real_escape_string($_SESSION['user_id']);
  }
  if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $id=intval($_GET['id']);
    $sql = "SELECT tag FROM tag WHERE user_id='$uid' AND problem_id='$id'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $my_tag = $row['tag'];
    $result->free();
  }
  /* 获取我的标签 end */
  /* 判断当前用户是否已AC本题 start*/
  $is_solved = false;
  if (isset($_SESSION['user_id']) && isset($_GET['id'])) {
    $uid = $mysqli->real_escape_string($_SESSION['user_id']);
    $id=intval($_GET['id']);
    $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$id' AND result='4'";
    $result = $mysqli->query($sql);
    if ($result->num_rows) $is_solved = true;
    $result->free();
  }
  /* 判断当前用户是否已AC本题 end*/
  $real_id=0;
  $pr_flag=false;
  $co_flag=false;
  if (isset($_GET['id'])) { // 如果是比赛外的题目
    $id=intval($_GET['id']);
    $real_id=$id;
    //require("oj-header.php");
    $res = $mysqli->query("SELECT problemset from problem WHERE problem_id=$id");
    $set_name = $res->fetch_array()[0];
    $now=strftime("%Y-%m-%d %H:%M",time());
    if (HAS_PRI("see_hidden_".$set_name."_problem")){
      $sql="SELECT * FROM `problem` WHERE `problem_id`=$id";
    }
    else 
      $sql=<<<SQL
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
SQL;
    $pr_flag=true;

  } else if (isset($_GET['cid']) && isset($_GET['pid'])) { // 如果是比赛中的题目
    $sql="SELECT unix_timestamp(end_time) FROM contest WHERE contest_id={$_GET['cid']}";
    $end_time=$mysqli->query($sql)->fetch_array()[0];
    $cid=intval($_GET['cid']);
    $pid=intval($_GET['pid']);

    if (isset($_SESSION['contest_id']) && $_SESSION['contest_id']!=$_GET['cid']) {
      $view_errors = "<font style='color:red;text-decoration:underline;'>You can only enter the correspond contest!</font>";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }
    $sql="SELECT `problem_id` FROM `contest_problem` WHERE `contest_id`=$cid AND `num`=$pid";
    $res=$mysqli->query($sql);
    $real_id=$res->fetch_array()[0];
    $sql="SELECT problemset FROM `problem` WHERE `problem_id`=$real_id";
    $res = $mysqli->query($sql);
    $set_name = $res->fetch_array()[0];

    if (!HAS_PRI("edit_contest"))// if you can edit contest, you can see these problem in passing
      $sql="SELECT langmask,private,defunct,user_limit FROM `contest` WHERE `contest_id`=$cid AND `start_time`<='$now'";
    else
      $sql="SELECT langmask,private,defunct,user_limit FROM `contest` WHERE `contest_id`=$cid";
    $result=$mysqli->query($sql);
    $rows_cnt=$result->num_rows;
    $row=$result->fetch_array();
    
    $contest_ok=true;
    if ($row['user_limit']=="Y" && $_SESSION['contest_id']!=$cid) $contest_ok=false;
    if ($row[1] && !isset($_SESSION['c'.$cid])) $contest_ok=false;
    if ($row[2]=='Y') $contest_ok=false;
    if (HAS_PRI("edit_contest")) $contest_ok=true;

    $ok_cnt=$rows_cnt==1;              
    $langmask=$row[0];
    $result->free();
    if ($ok_cnt!=1){
      // not started
      $view_errors=  "No such contest or the contest not started!";
      require("template/".$OJ_TEMPLATE."/error.php");
      exit(0);
    }else{
      // started
      $sql="SELECT * FROM `problem` WHERE `problem_id`=$real_id";
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

  $result=$mysqli->query($sql) or die($mysqli->error);
  if ($result->num_rows!=1){
    $view_errors="";
    if(isset($_GET['id'])){
      $id=intval($_GET['id']);
      $result->free();
      $sql="SELECT  contest.`contest_id` , contest.`title`,contest_problem.num FROM `contest_problem`,`contest` WHERE contest.contest_id=contest_problem.contest_id and `problem_id`=$id ORDER BY `num`";
      //echo $sql;
      $result=$mysqli->query($sql);
      if($i=$result->num_rows){
         $view_errors.= "This problem is in Contest(s) below:<br>";
         for (;$i>0;$i--){
           $row=$result->fetch_row();
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
    $row=$result->fetch_object();
    $view_title= $row->title;
  }
  $result->free();


  /* 获取标签 start */
  $tag = array();
  $sql = "SELECT tag, COUNT(tag) AS sum FROM (SELECT tag FROM tag WHERE problem_id='$id') AS t GROUP BY tag ORDER BY sum DESC LIMIT 10";
  $result = $mysqli->query($sql);
  for ($i=0; $tag_row=$result->fetch_array(); ++$i) {
    $tag[$i] = $tag_row['tag'];
  }
  $result->free();
  /* 获取标签 end */

  //get try times and determin if he can see the video START
  $can_see_video=false;
  $try_times=0;
  if(isset($_SESSION['user_id'])){
    $sql = "SELECT solution_id FROM solution WHERE user_id='$uid' AND problem_id='$real_id'";
    $res=$mysqli->query($sql);
    $try_times=$res->num_rows;
    if($try_times>$VIDEO_SUBMIT_TIME) $can_see_video=true;
  }
  //get try times and determin if he can see the video END

  //get the sample input/output START
  $samples=array();
  $sql="SELECT input, output, show_after FROM problem_samples WHERE problem_id='$real_id' AND show_after<=$try_times ORDER BY sample_id";
  $res=$mysqli->query($sql);
  while($r=$res->fetch_array()){
    array_push($samples, array(
      "input" => $r['input'],
      "output" => $r['output'],
      "show_after" => $r['show_after'],
    ));
  }
  //get the sample input/output END



/////////////////////////Template
require("template/".$OJ_TEMPLATE."/problem.php");
/////////////////////////Common foot
if(file_exists('./include/cache_end.php'))
        require_once('./include/cache_end.php');
?>

