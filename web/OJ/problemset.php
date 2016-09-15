<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.26
  **/
?>

<?php

  $OJ_CACHE_SHARE=false;
  $cache_time=60;
  require_once('./include/cache_start.php');
  require_once('./include/setlang.php');
  $view_title= "Problem Set";

  //get all problemsets START

  //get all problemsets END
  /* 获取OJ start */
  if (isset($_GET['OJ'])) $OJ = $_GET['OJ'];
  else $OJ = "all";
  /* 获取OJ end */


  $page_cnt = 100;


  //remember page
  $page="1";
  if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
  }
  //end of remember page


  /* 是否显示标签 start */
  $show_tag = true;

  if (isset($_SESSION['user_id']) && !isset($_SESSION['contest_id'])) {
    $uid = $_SESSION['user_id'];
    $sql = "SELECT tag FROM users WHERE user_id='$uid'";
    $result = mysql_query($sql);
    $row_h = mysql_fetch_array($result);
    mysql_free_result($result);
    if ($row_h['tag'] == "N") $show_tag = false;
  } else if (isset($_SESSION['tag'])) {
    if ($_SESSION['tag'] == "N") $show_tag = false;
    else $show_tag = true;
  }

  if ($show_tag) $_SESSION['tag'] = "Y";
  else $_SESSION['tag'] = "N";
  /* 是否显示标签 end */


  /* 获取当前用户提交过的题目 start */
  $sub_arr=Array();
  if (isset($_SESSION['user_id'])) {
    $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'"." group by `problem_id`";
    $result=@mysql_query($sql) or die(mysql_error());
    while ($row=mysql_fetch_array($result))
      $sub_arr[$row[0]]=true;
  }
  /* 获取当前用户提交过的题目 end */


  /* 获取当前用户已AC的题目 start */
  $acc_arr=Array();
  if (isset($_SESSION['user_id'])) {
    $sql="SELECT `problem_id` FROM `solution` WHERE `user_id`='".$_SESSION['user_id']."'"." AND `result`=4"." group by `problem_id`";
    $result=@mysql_query($sql) or die(mysql_error());
    while ($row=mysql_fetch_array($result))
      $acc_arr[$row[0]]=true;
  }
  /* 获取当前用户已AC的题目 end */


  /* 获取sql语句中的筛选部分 start */
  if(isset($_GET['search'])&&trim($_GET['search'])!="") {
    $search=mysql_real_escape_string($_GET['search']);
      $filter_sql="title like '%$search%' or source like '%$search%' or author like '%$search%' OR tag1 like '%$search%' OR tag2 like '%$search%' OR tag3 like '%$search%'"; 
  } else {
      $filter_sql="1";
  }

  /* 获取sql语句中的筛选部分 end */
  $res_set = mysql_query("SELECT set_name FROM problemset");
  $first = true;
  $sql = "";
  $cnt = 0;
  while($set_name=mysql_fetch_array($res_set)[0]){
    if($OJ=='all' || $OJ==$set_name){
      if(HAS_PRI("see_hidden_".$set_name."_problem")){
        $t_sql=" FROM `problem` WHERE $filter_sql AND problemset='$set_name'";
      }
      else{
        $now=strftime("%Y-%m-%d %H:%M",time());
        $t_sql=<<<sql
        FROM 
          problem
        WHERE 
          defunct='N' 
          AND $filter_sql
          AND problemset='$set_name' 
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
      }
      //count the number of problem START
      $res = mysql_query("SELECT COUNT('problem_id')".$t_sql);
      $cnt += mysql_fetch_array($res)[0];
      //count the number of problem END

      $t_sql = "SELECT `problem_id`,`title`,author,`source`,`submit`,`accepted`,score, tag1, tag2, tag3 ".$t_sql;
      if($first) $first = false;
      else $t_sql = " UNION ".$t_sql;
      $sql .= $t_sql;


    }
  }
  $sql.=" ORDER BY `problem_id`";
  $st=($page-1)*$page_cnt;
  $sql.=" LIMIT $st,$page_cnt";

  if($first) $sql="";
  //echo "sql:".$sql."\n";
  /* 获取数据库查询语句 end */


  //echo "<pre>".htmlentities($sql)."</pre>";
  $result=mysql_query($sql) or die(mysql_error());



  /* 计算页数cnt start */
  $view_total_page=$cnt/$page_cnt+($cnt%$page_cnt?1:0);// 页数
  $cnt=0;
  $view_problemset=Array();
  $i=0;
  /* 计算页数cnt end */

  /* 把结果放入表格 start */
  while ($row=mysql_fetch_object($result)) {
    $view_problemset[$i]=Array();

    // 获取problem ID
    $p_id = $row->problem_id;
    // 将信息放入表格
    if (isset($sub_arr[$p_id])) {
      if (isset($acc_arr[$p_id])) 
        $view_problemset[$i][0] = "<td style='width:30px'><font color='green'>Y</font></td>";
      else 
        $view_problemset[$i][0] = "<td style='width:30px'><font color='red'>N</font></td>";
    } else {
      $view_problemset[$i][0] = "<td style='width:30px'></td>";
    }
    $view_problemset[$i][1] = "<td>".$p_id."</td>";
    $view_problemset[$i][2] = "<td><a href='problem.php?id=".$p_id."'>".$row->title."</a></td>";
    $view_problemset[$i][3] = "<td >";
    if ($show_tag) {
        $view_problemset[$i][3] .= "<span class='am-badge am-badge-danger'>".$row->tag1."</span>";
        $view_problemset[$i][3] .= "<span class='am-badge am-badge-warning'>".$row->tag2."</span>";
        $view_problemset[$i][3] .= "<span class='am-badge am-badge-primary'>".$row->tag3."</span>";
    }
    $view_problemset[$i][3] .= "</td>";
    $view_problemset[$i][4] = "<td><nobr>".mb_substr($row->author,0,40,'utf8')."</nobr></td >";
    $view_problemset[$i][5] = "<td><nobr>".mb_substr($row->source,0,40,'utf8')."</nobr></td >";
    $view_problemset[$i][6]="<td><a href='status.php?problem_id=".$row->problem_id."&jresult=4'>".$row->accepted."</a>/"."<a href='status.php?problem_id=".$row->problem_id."'>".$row->submit."</a></td>";
    $view_problemset[$i][7]="<td >".$row->score."</td>";
    $i++;
  }
  mysql_free_result($result);
  /* 查询并把结果放入表格 end */

  require("template/".$OJ_TEMPLATE."/problemset.php");
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');

?>
