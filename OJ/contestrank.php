<?php
  /**
   * This file is modified!
   * by yybird
   * @2016.05.24
  **/
?>

<?php
  $OJ_CACHE_SHARE=true;
  $cache_time=10;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  require_once("./include/const.inc.php");
  require_once("./include/my_func.inc.php");

  $view_title= $MSG_CONTEST.$MSG_RANKLIST;
  $title="";

  class TM {
    var $solved=0;
    var $time=0;
    var $p_wa_num;
    var $p_ac_sec;
    var $user_id;
    var $nick;
    var $real_name;
    function TM(){
      $this->solved=0;
      $this->time=0;
      $this->p_wa_num=array(0);
      $this->p_ac_sec=array(0);
    }
    function Add($pid,$sec,$res){
      if (isset($this->p_ac_sec[$pid])&&$this->p_ac_sec[$pid]>0) return;
      if ($res!=4){
        if(isset($this->p_wa_num[$pid])){
                $this->p_wa_num[$pid]++;
        }else{
                $this->p_wa_num[$pid]=1;
        }
      } else {
        $this->p_ac_sec[$pid]=$sec;
        $this->solved++;
        if(!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid]=0;
        $this->time+=$sec+$this->p_wa_num[$pid]*1200;
      }
    }
  }

  function s_cmp($A,$B){
    if ($A->solved!=$B->solved) return $A->solved<$B->solved;
    else return $A->time>$B->time;
  }

// contest start time
  if (!isset($_GET['cid'])) die("No Such Contest!");
  $cid=intval($_GET['cid']);

  $sql="SELECT `start_time`,`title`,`end_time`,user_limit FROM `contest` WHERE `contest_id`='$cid'";
//$result=mysql_query($sql) or die(mysql_error());
//$rows_cnt=mysql_num_rows($result);
  if($OJ_MEMCACHE){
    require("./include/memcache.php");
    $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
  } else {
    $result = mysql_query($sql);// or die("Error! ".mysql_error());
    if($result) $rows_cnt = mysql_num_rows($result);
    else $rows_cnt=0;
  }


  $start_time=0;
  $end_time=0;
  $user_limit = 0;
  if ($rows_cnt>0){
  //      $row=mysql_fetch_array($result);
    if($OJ_MEMCACHE) $row=$result[0];
    else $row=mysql_fetch_array($result);
    $start_time=strtotime($row['start_time']);
    $end_time=strtotime($row['end_time']);
    $title=$row['title'];  
    $user_limit = $row['user_limit']=="Y"?1:0;
  }

  if(!$OJ_MEMCACHE) mysql_free_result($result);
  if ($start_time==0){
    $view_errors= "No Such Contest";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
  if ($start_time>time()){
    $view_errors= "Contest Not Started!";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
  if(!isset($OJ_RANK_LOCK_PERCENT)) $OJ_RANK_LOCK_PERCENT=0;
  $lock=$end_time-($end_time-$start_time)*$OJ_RANK_LOCK_PERCENT;




  $sql="SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`='$cid'";
  //$result=mysql_query($sql);
  if($OJ_MEMCACHE){
  //        require("./include/memcache.php");
    $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
  }else{
    $result = mysql_query($sql);// or die("Error! ".mysql_error());
    if($result) $rows_cnt=mysql_num_rows($result);
    else $rows_cnt=0;
  }

  if($OJ_MEMCACHE) $row=$result[0];
  else $row=mysql_fetch_array($result);

  //$row=mysql_fetch_array($result);
  $pid_cnt=intval($row['pbc']);
  if(!$OJ_MEMCACHE)mysql_free_result($result);

  /* 获取班级列表 start */
  $classSet = Array();
  if (!$user_limit) {
    $sql = "SELECT
              DISTINCT(class)
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
              on users.user_id=solution.user_id
            ORDER BY class";
    $result = mysql_query($sql) or die(mysql_error());
    while ($row=mysql_fetch_object($result)) $classSet[] = $row->class;
    mysql_free_result($result);
  } 
  $sql = "SELECT
            DISTINCT(class)
          FROM
            (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
            RIGHT JOIN (SELECT * FROM team WHERE contest_id='$cid') team
            on team.user_id=solution.user_id
          ORDER BY class";    
  $result = mysql_query($sql) or die(mysql_error());
  while ($row=mysql_fetch_object($result)) $classSet[] = $row->class;
  mysql_free_result($result);
  /* 获取班级列表 end */


  if(!$OJ_MEMCACHE) mysql_free_result($result);

/* origin sql
$sql="SELECT
        users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name
                FROM
                        (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                left join users
                on users.user_id=solution.user_id
        ORDER BY users.user_id,in_date";
*/


  /* 获取查询的SQL语句 start */
  $cls = $_GET['class']; // class
  if ($cls == "") {
    if (!$user_limit)
      $sql_u = "SELECT 
                  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class
                FROM
                  (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                  INNER join users
                  on users.user_id=solution.user_id
                ORDER BY users.user_id,in_date";
    $sql = "SELECT 
              team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
              INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
              on team.user_id=solution.user_id
            ORDER BY team.user_id,in_date";
  } else if ($cls == "null") {
    if (!$user_limit)
      $sql_u = "SELECT
                  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class
                FROM
                  (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                  INNER join users
                  on users.user_id=solution.user_id
                WHERE users.class='null' or users.class is null
                ORDER BY users.user_id,in_date";
    $sql = "SELECT
              team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class
            FROM
              (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
              INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
              on team.user_id=solution.user_id
            WHERE team.class='null' or team.class is null
            ORDER BY team.user_id,in_date";
  } else {
    if (!$user_limit)
      $sql_u = "SELECT
                  users.user_id,users.nick,solution.result,solution.num,solution.in_date,users.real_name,users.class
                FROM
                  (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                  INNER join users
                  on users.user_id=solution.user_id
                WHERE users.class='$cls'
                ORDER BY users.user_id,in_date";
      $sql = "SELECT
                team.user_id,team.nick,solution.result,solution.num,solution.in_date,team.class
              FROM
                (select * from solution where solution.contest_id='$cid' and num>=0 ) solution
                INNER JOIN (SELECT * FROM team WHERE contest_id='$cid') team
                on team.user_id=solution.user_id
              WHERE team.class='$cls'
              ORDER BY team.user_id,in_date";
  }
  // echo $sql;
  /* 获取查询的SQL语句 end */

  /* 执行查询 start */  
  if($OJ_MEMCACHE){
    // require("./include/memcache.php");
    $result = mysql_query_cache($sql);// or die("Error! ".mysql_error());
    if($result) $rows_cnt=count($result);
    else $rows_cnt=0;
  } else {
    $result = mysql_query($sql);// or die("Error! ".mysql_error());
    if($result) $rows_cnt=mysql_num_rows($result);
    else $rows_cnt=0;
  }
  /* 执行查询 end */  


  /* 获取查询结果 start */
  $user_cnt=0;
  $user_name='';
  $U=array();
  $U[$user_cnt]=new TM();
  $U[0]->solved=-1;

  // 查询team部分
  for ($i=0; $i<$rows_cnt; $i++){
    if($OJ_MEMCACHE) $row=$result[$i];
    else $row=mysql_fetch_array($result);

    $n_user=$row['user_id'];
    if (strcmp($user_name,$n_user)){
      $user_cnt++;
      $U[$user_cnt]=new TM();

      $U[$user_cnt]->user_id=$row['user_id'];
      $U[$user_cnt]->nick=$row['nick'];
      $U[$user_cnt]->real_name = $row['real_name'];
      $user_name=$n_user;
    }
    if(time()<$end_time&&$lock<strtotime($row['in_date']))
      $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0);
    else
      $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']));
  }

  // 查询user部分
  if (isset($sql_u)) {
    $result = mysql_query($sql_u);// or die("Error! ".mysql_error());
    if($result) $rows_cnt=mysql_num_rows($result);
    else $rows_cnt=0;
  }
  for ($i=0; $i<$rows_cnt; $i++){
    if($OJ_MEMCACHE) $row=$result[$i];
    else $row=mysql_fetch_array($result);

    $n_user=$row['user_id'];
    if (strcmp($user_name,$n_user)){
      $user_cnt++;
      $U[$user_cnt]=new TM();

      $U[$user_cnt]->user_id=$row['user_id'];
      $U[$user_cnt]->nick=$row['nick'];
      $U[$user_cnt]->real_name = $row['real_name'];
      $user_name=$n_user;
    }
    if(time()<$end_time&&$lock<strtotime($row['in_date']))
      $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,0);
    else
      $U[$user_cnt]->Add($row['num'],strtotime($row['in_date'])-$start_time,intval($row['result']));
  }
  /* 获取查询结果 start */


  if(!$OJ_MEMCACHE) mysql_free_result($result);
  usort($U,"s_cmp");

  ////firstblood
  $first_blood=array();
  for($i=0;$i<$pid_cnt;$i++){
     $sql="select user_id from solution where contest_id=$cid and result=4 and num=$i order by in_date limit 1";
     $result=mysql_query($sql);
     $row_cnt=mysql_num_rows($result);
     $row=mysql_fetch_array($result);
     if($row_cnt==1){
        $first_blood[$i]=$row['user_id'];
     }else{
        $first_blood[$i]="";
     }

  }


  /////////////////////////Template
  require("template/".$OJ_TEMPLATE."/contestrank.php");
  /////////////////////////Common foot
  if(file_exists('./include/cache_end.php'))
          require_once('./include/cache_end.php');
?>
