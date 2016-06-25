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


  /* 获取OJ start */
  if (isset($_GET['OJ'])) $OJ = $_GET['OJ'];
  else $OJ = "HZNU";
  /* 获取OJ end */


  /* 选择数据库连接 start */
  if ($OJ!="HZNU" && $OJ!="C") { // 不是HZNUOJ的题目，则连接转入vjudge
    $connvj = mysql_connect($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,true);
    if (!$connvj) die('Could not connect: ' . mysql_error());
    mysql_select_db("vhoj", $connvj);
    mysql_query("set names utf8");
  }
  /* 选择数据库连接 end */


  /* 计算页数cnt start */
  $first = 1000; // 题号以几开头
  $page_cnt = 100; // 每页显示多少道题
  $border = 500000; // 普通题目和C语言题目的分界
  $sql = "";

  // 获取数据库语句
  if ($OJ == "HZNU") 
    $sql="SELECT max(`problem_id`) as upid FROM `problem` WHERE problem_id<'$border'";
  else if ($OJ == "C") {
    $first = 500000;
    $sql = "SELECT max(problem_id) AS upid FROM problem WHERE problem_id>='$border'";
  } else if ($OJ=="CodeForces" || $OJ=="UVA")
    $sql = "SELECT COUNT(C_originProb) AS num FROM t_problem WHERE C_originOJ='$OJ'";
  else
    $sql="SELECT MAX(C_originProb) AS upid FROM t_problem WHERE C_originOJ='$OJ'";

  // 执行数据库操作
  $result=mysql_query($sql);
  $row=mysql_fetch_object($result);
  if ($OJ=="CodeForces" || $OJ=="UVA") {
    $cnt=intval($row->num)/$page_cnt; // 页数
  } else {
    $cnt=intval($row->upid)-$first;
    $cnt=$cnt/$page_cnt; // 页数
  }
  mysql_free_result($result);
  /* 计算页数cnt end */


  //remember page
  $page="1";
  if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
    if($OJ=="HZNU" && isset($_SESSION['user_id'])){
      $sql="UPDATE users SET volume=$page WHERE user_id='".$_SESSION['user_id']."'";
      mysql_query($sql);
    } else if ($OJ=="C" && isset($_SESSION['user_id'])) {
      $sql="UPDATE users SET volume_c=$page WHERE user_id='".$_SESSION['user_id']."'";
      mysql_query($sql);
    }
  } else {
    if (($OJ=="HZNU") && isset($_SESSION['user_id'])) {
      $sql="SELECT volume FROM users WHERE user_id='".$_SESSION['user_id']."'";
      $result=@mysql_query($sql);
      $row=mysql_fetch_array($result);
      $page=intval($row[0]);
    } else if ($OJ=="C" && isset($_SESSION['user_id'])) {
      $sql="SELECT volume_c FROM users WHERE user_id='".$_SESSION['user_id']."'";
      $result=@mysql_query($sql);
      $row=mysql_fetch_array($result);
      $page=intval($row[0]);
    }
    mysql_free_result($result);
    if(!is_numeric($page)||$page<0)
        $page='1';
  }
  //end of remember page


  /* 计算该页起始题号和结束题号 start */
  if ($OJ == "CodeForces") {
    $pstart = ((intval($page)-1)*25+1)."A";
    $pend = (intval($page)*25)."E";
  } else {
    $pstart = $first+$page_cnt*intval($page)-$page_cnt;
    $pend=$pstart+$page_cnt;
  }
  /* 计算该页起始题号和结束题号 end */


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
    if ($OJ == "HZNU") 
      $sql="SELECT `problem_id` FROM `solution` WHERE problem_id<'$border' AND `user_id`='".$_SESSION['user_id']."'"." group by `problem_id`";
    else if ($OJ == "C")
      $sql="SELECT `problem_id` FROM `solution` WHERE problem_id>='$border' AND `user_id`='".$_SESSION['user_id']."'"." group by `problem_id`";
    else
      $sql = "SELECT C_ORIGIN_PROB FROM t_submission WHERE C_USERNAME='".$_SESSION['user_id']."' AND C_ORIGIN_OJ='$OJ' GROUP BY C_ORIGIN_PROB";
    $result=@mysql_query($sql) or die(mysql_error());
    while ($row=mysql_fetch_array($result))
      $sub_arr[$row[0]]=true;
  }
  /* 获取当前用户提交过的题目 end */


  /* 获取当前用户已AC的题目 start */
  $acc_arr=Array();
  if (isset($_SESSION['user_id'])) {
    if ($OJ == "HZNU") 
      $sql="SELECT `problem_id` FROM `solution` WHERE problem_id<'$border' AND `user_id`='".$_SESSION['user_id']."'"." AND `result`=4"." group by `problem_id`";
    else if ($OJ == "C")
      $sql="SELECT `problem_id` FROM `solution` WHERE problem_id>='$border' AND `user_id`='".$_SESSION['user_id']."'"." AND `result`=4"." group by `problem_id`";
    else
      $sql = "SELECT C_ORIGIN_PROB FROM t_submission WHERE C_USERNAME='".$_SESSION['user_id']."' AND C_ORIGIN_OJ='$OJ' AND C_STATUS_CANONICAL='AC' GROUP BY C_ORIGIN_PROB";
    $result=@mysql_query($sql) or die(mysql_error());
    while ($row=mysql_fetch_array($result))
      $acc_arr[$row[0]]=true;
  }
  /* 获取当前用户已AC的题目 end */


  /* 获取sql语句中的筛选部分 start */
  if(isset($_GET['search'])&&trim($_GET['search'])!="") {
    $search=mysql_real_escape_string($_GET['search']);
    if ($OJ == "HZNU")
      $filter_sql=" problem_id<'$border' AND ( title like '%$search%' or source like '%$search%' or author like '%$search%' OR tag1 like '%$search%' OR tag2 like '%$search%' OR tag3 like '%$search%')"; 
    else if ($OJ == "C")
      $filter_sql=" problem_id>='$border' AND ( title like '%$search%' or source like '%$search%' or author like '%$search%' OR tag1 like '%$search%' OR tag2 like '%$search%' OR tag3 like '%$search%')"; 
    else 
      $filter_sql=" ( C_TITLE like '%$search%' or C_SOURCE like '%$search%')";  
  } else {
    if ($OJ=="HZNU" || $OJ=="C") 
      $filter_sql="  `problem_id`>='".strval($pstart)."' AND `problem_id`<'".strval($pend)."' ";
    else if ($OJ=="ZOJ" || $OJ=="HDU" || $OJ=="POJ")
      $filter_sql="  C_originProb>='".strval($pstart)."' AND C_originProb<'".strval($pend)."' ";
    else
      $filter_sql = "1";
  }
  /* 获取sql语句中的筛选部分 end */
  

  /* 获取数据库查询语句 start */
  if ($OJ=="HZNU" || $OJ=="C") {
    if (($OJ=="HZNU"&&$GE_A) || ($OJ=="C"&&$GE_TA)) { // 有查看隐藏题目权限
      $sql="SELECT `problem_id`,`title`,author,`source`,`submit`,`accepted`,score, tag1, tag2, tag3 FROM `problem` WHERE $filter_sql ";
    } else { // 无查看隐藏题目权限
      $now=strftime("%Y-%m-%d %H:%M",time());
      $sql="SELECT `problem_id`,`title`,author,`source`,`submit`,`accepted`,score, tag1, tag2, tag3 FROM `problem` ".
            "WHERE `defunct`='N' and $filter_sql AND `problem_id` NOT IN (
              SELECT `problem_id` FROM `contest_problem` WHERE `contest_id` IN (
                SELECT `contest_id` FROM `contest` WHERE 
                (`end_time`>'$now' or private=1)and `defunct`='N'
              )
            ) ";
    }
    $sql.=" ORDER BY `problem_id`";
  } else { // 查看VJ题目
    $sql = "SELECT DISTINCT C_ID,C_originProb,C_TITLE,C_SOURCE,C_originOJ FROM t_problem WHERE C_originOJ='$OJ' AND C_TITLE!='N/A' AND $filter_sql ORDER BY C_originProb";
  }
  /* 获取数据库查询语句 end */


  $view_total_page=$cnt+1;
  $cnt=0;
  $view_problemset=Array();
  $i=0;


  /* 查询并把结果放入表格 start */
  $result=mysql_query($sql) or die(mysql_error());
  while ($row=mysql_fetch_object($result)) {
    $view_problemset[$i]=Array();

    // 获取problem ID
    if ($OJ=="HZNU" || $OJ=="C")
      $p_id = $row->problem_id;
    else
      $p_id = $row->C_originProb;

    // 将信息放入表格
    if (isset($sub_arr[$p_id])) {
      if (isset($acc_arr[$p_id])) 
        $view_problemset[$i][0] = "<td class='am-text-center' style='width:30px'><font color='green'>Y</font></td>";
      else 
        $view_problemset[$i][0] = "<td class='am-text-center' style='width:30px'><font color='red'>N</font></td>";
    } else {
      $view_problemset[$i][0] = "<td class='am-text-center' style='width:30px'></td>";
    }
    $view_problemset[$i][1]="<td class='am-text-center'>".$p_id."</td>";
    if ($OJ=="HZNU" || $OJ=="C") {
      $view_problemset[$i][2]="<td><a href='problem.php?id=".$p_id."'>".$row->title."</a></td>";
      $view_problemset[$i][3] = "<td>";
      if ($show_tag) {
          $view_problemset[$i][3] .= "<span class='am-badge am-badge-danger am-round'>".$row->tag1."</span>";
          $view_problemset[$i][3] .= "<span class='am-badge am-badge-warning am-round'>".$row->tag2."</span>";
          $view_problemset[$i][3] .= "<span class='am-badge am-badge-primary am-round'>".$row->tag3."</span>";
      }
      $view_problemset[$i][3] .= "</td>";
      $view_problemset[$i][4] = "<td class='am-text-center'><nobr>".mb_substr($row->author,0,40,'utf8')."</nobr></td >";
      $view_problemset[$i][5] = "<td class='am-text-center'><nobr>".mb_substr($row->source,0,40,'utf8')."</nobr></td >";
      $view_problemset[$i][6]="<td class='am-text-center'><a href='status.php?problem_id=".$row->problem_id."&jresult=4'>".$row->accepted."</a>/"."<a href='status.php?problem_id=".$row->problem_id."'>".$row->submit."</a></td>";
      $view_problemset[$i][7]="<td class='am-text-center'>".$row->score."</td>";
    } else {
      $view_problemset[$i][2]="<td><a href='".$VJ_URL."/problem/viewProblem.action?id=".$row->C_ID."'>".$row->C_TITLE."</a></td>";
      $view_problemset[$i][3] = "<td></td>";
      $view_problemset[$i][4] = "<td></td>";
      $view_problemset[$i][5]="<td class='am-text-center'><nobr>".strip_tags(mb_substr($row->C_SOURCE,0,40,'utf8'))."</nobr></td>";
      // 获取AC次数
      $sql_tmp = "SELECT COUNT(*) AS ac_num, COUNT(DISTINCT C_USER_ID) AS ac_user 
                  FROM t_submission WHERE C_ORIGIN_PROB='".$row->C_originProb."' AND C_ORIGIN_OJ='$OJ' AND C_STATUS_CANONICAL='AC'";
      $result_tmp = mysql_query($sql_tmp) or die(mysql_error());
      $row_tmp = mysql_fetch_object($result_tmp);
      $AC_num = $row_tmp->ac_num;
      $AC_user = $row_tmp->ac_user;
      mysql_free_result($result_tmp);
     // 获取提交次数
      $sql_tmp = "SELECT COUNT(*) AS sub_num, COUNT(DISTINCT C_USER_ID) AS sub_user 
                  FROM t_submission WHERE C_ORIGIN_PROB='".$row->C_originProb."' AND C_ORIGIN_OJ='$OJ'";
      $result_tmp = mysql_query($sql_tmp) or die(mysql_error());
      $row_tmp = mysql_fetch_object($result_tmp);
      $sub_num = $row_tmp->sub_num;
      $sub_user = $row_tmp->sub_user;
      mysql_free_result($result_tmp);
      $view_problemset[$i][6]="<td class='am-text-center'><a href='".$VJ_URL."/problem/status.action#un=&OJId=$OJ&probNum=".$row->C_originProb."&res=1&orderBy=run_id'>".$AC_num."</a>/<a href='".$VJ_URL."/problem/status.action#un=&OJId=$OJ&res=0&probNum=".$row->C_originProb."'>".$sub_num."</a></td>";
      // 获取vjudge上的用户数
      $sql_tmp = "SELECT COUNT(*) AS cnt FROM t_user";
      $result_tmp = mysql_query($sql_tmp) or die(mysql_error());
      $row_tmp = mysql_fetch_object($result_tmp);
      $user_cnt = $row_tmp->cnt;
      mysql_free_result($result_tmp);
      // 计算分数
      $score = 100.0 * (1-($AC_user+$sub_user/2.0)/$user_cnt);
      if ($score < 10) $score = 10;
      $view_problemset[$i][7]="<td class='am-text-center'>".round($score, 2)."</td>";
    }
    $i++;
  }
  mysql_free_result($result);
  /* 查询并把结果放入表格 end */

  require("template/".$OJ_TEMPLATE."/problemset.php");
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');

?>
