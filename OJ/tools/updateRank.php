<?php
  /**
   * This file is created
   * by yybird
   * @2015.06.27
   * last modified
   * by yybird
   * @2015.07.03
  **/
?>

<?php

	require_once('./include/db_info.inc.php');
  
  class User {
    public $user_id; // 用户名
    public $strength; // 实力
    public $level; // 等级
    public $level_name; // 等级名称
    public $color; // 颜色
    public $ZJU;
    public $HDU;
    public $PKU;
    public $UVA;
    public $CF;
  }

  // 获取解题数大于10的用户数量存入user_cnt_divisor
  $sql = "SELECT user_id FROM users WHERE solved>10";
  $result_user  = mysql_query($sql) or die(mysql_error());
  if($result_user) $user_cnt_divisor = mysql_num_rows($result_user);
  else $user_cnt_divisor = 1;
  mysql_free_result($result_user);
  // echo $user_cnt_divisor."<br>";

  // 获取用户总量
  $sql = "SELECT user_id FROM users";
  $result_user  = mysql_query($sql) or die(mysql_error());
  if($result_user) $user_cnt = mysql_num_rows($result_user);
  else $user_cnt = 0;

  $user_info = array();
  for ($i=0; $i<$user_cnt; $i++) {
    $row_user = mysql_fetch_object($result_user);
    $user_info[$i] = new User();
    $user_info[$i]->user_id = $row_user->user_id;
  }
  mysql_free_result($result_user);
  // echo $user_cnt."<br>";

  // 获取hznuoj分数
  for ($i=0; $i<$user_cnt; $i++) {
    
    // 获取用户
    $user_mysql = $user_info[$i]->user_id;

    // 获取该用户AC的所有题目，存入result
    $sql = "SELECT DISTINCT problem_id FROM solution WHERE user_id='".$user_mysql."' AND result=4";
    $result = mysql_query($sql) or die(mysql_error());

    // 获取该用户AC的题目数量，存入rows_cnt
    if($result) $rows_cnt = mysql_num_rows($result);
    else $rows_cnt = 0;
    
    $user_info[$i]->strength = 0;
    $user_info[$i]->level_name = "斗之气一段";
    $user_info[$i]->color = "#E0E0E0";

    // 对于每道AC的题目，计算其分数，并加至strength
    for ($j=0; $j<$rows_cnt; $j++) {
      $row = mysql_fetch_object($result);
      $prob_id = $row->problem_id;
      $sql = "SELECT solved_user, submit_user FROM problem WHERE problem_id=".$prob_id;
      $y_result = mysql_query($sql) or die(mysql_error());
      $y_row = mysql_fetch_object($y_result);
      $solved = $y_row->solved_user;
      $submit = $y_row->submit_user;
      $scores = 100.0 * (1-($solved+$submit/2.0)/$user_cnt_divisor);
      if ($scores < 10) $scores = 10;
      $user_info[$i]->strength += $scores;
      mysql_free_result($y_result);
    }
    mysql_free_result($result);

  }

  // 连接转入vjudge
  $connvj = mysql_connect($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,true);
  if (!$connvj) die('Could not connect: ' . mysql_error());
  mysql_select_db("vhoj", $connvj);
  mysql_query("set names utf8");

  // 获取vjudge用户数存入user_cnt_divisor
  $sql = "SELECT C_USERNAME FROM t_user";
  $result_user  = mysql_query($sql) or die(mysql_error());
  if($result_user) $user_cnt_divisor = mysql_num_rows($result_user);
  else $user_cnt_divisor = 1;
  mysql_free_result($result_user);
  //echo $user_cnt_divisor;

  // 获取各个OJ的解题数
  for ($i=0; $i<$user_cnt; $i++) {
    // 获取用户
    $user_mysql = $user_info[$i]->user_id;
    
    // ZOJ
    $sql = "SELECT COUNT(DISTINCT C_ORIGIN_PROB) AS num FROM t_submission WHERE C_ORIGIN_OJ='ZOJ' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_object($result);
    $user_info[$i]->ZJU = $row->num;
    mysql_free_result($result);

    // HDOJ
    $sql = "SELECT COUNT(DISTINCT C_ORIGIN_PROB) AS num FROM t_submission WHERE C_ORIGIN_OJ='HDU' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_object($result);
    $user_info[$i]->HDU = $row->num;
    mysql_free_result($result);

    // POJ
    $sql = "SELECT COUNT(DISTINCT C_ORIGIN_PROB) AS num FROM t_submission WHERE C_ORIGIN_OJ='POJ' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_object($result);
    $user_info[$i]->PKU = $row->num;
    mysql_free_result($result);   

    // UVA
    $sql = "SELECT COUNT(DISTINCT C_ORIGIN_PROB) AS num FROM t_submission WHERE C_ORIGIN_OJ='UVA' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_object($result);
    $user_info[$i]->UVA = $row->num;
    mysql_free_result($result);

    // Codeforces
    $sql = "SELECT COUNT(DISTINCT C_ORIGIN_PROB) AS num FROM t_submission WHERE C_ORIGIN_OJ='CodeForces' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
    $result = mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_object($result);
    $user_info[$i]->CF = $row->num;
    mysql_free_result($result);

  }

  // 获取vjudge分数
  for ($i=0; $i<$user_cnt; $i++) {

    // 获取用户
    $user_mysql = $user_info[$i]->user_id;

    // 获取该用户AC的所有题目，存入result
    $sql = "SELECT DISTINCT C_PROBLEM_ID FROM t_submission WHERE C_USERNAME='".$user_mysql."' AND C_STATUS='Accepted'";
    $result = mysql_query($sql) or die(mysql_error());

    // 获取该用户AC的题目数量，存入rows_cnt
    if($result) $rows_cnt = mysql_num_rows($result);
    else $rows_cnt = 0;

    // 对于每道AC的题目，计算其分数，并加至strength
    for ($j=0; $j<$rows_cnt; $j++) {
      // 获取题号
      $row = mysql_fetch_object($result);
      $prob_id = $row->C_PROBLEM_ID;
      // 获取AC人数
      $sql = "SELECT COUNT(DISTINCT C_USER_ID) AS ac_user FROM t_submission WHERE C_PROBLEM_ID=".$prob_id." AND C_STATUS='Accepted'";
      $y_result = mysql_query($sql) or die(mysql_error());
      $y_row = mysql_fetch_object($y_result);
      $solved = $y_row->ac_user;
      // 获取提交人数
      $sql = "SELECT COUNT(DISTINCT C_USER_ID) AS sub_user FROM t_submission WHERE C_PROBLEM_ID=".$prob_id;
      $submit = $y_row->sub_user;
      $scores = 100.0 * (1-($solved+$submit/2.0)/$user_cnt_divisor);
      if ($scores < 10) $scores = 10;
      $user_info[$i]->strength += $scores;
      mysql_free_result($y_result);
    }
    // echo $user_mysql.":".$user_info[$i]->strength."<br>";

    mysql_free_result($result);

  }

  // 连接转回hustoj
  $conn = mysql_connect($DB_HOST,$DB_USER,$DB_PASS,true);
  if (!$conn) die('Could not connect: ' . mysql_error());
  mysql_select_db("jol", $conn);
  mysql_query("set names utf8");


  require_once('./include/rank.inc.php');

  for ($i=0; $i<$user_cnt; $i++) {
    
    // 获取用户
    $user_mysql = $user_info[$i]->user_id;

    // 根据数组计算该实力对应的等级和颜色
    if ($user_info[$i]->strength > 20100*10) {
      $user_info[$i]->color = "#6C3365";
      $user_info[$i]->level_name = "斗战胜佛";
    } else for ($j=0; $j<210; $j++) {
      if ($user_info[$i]->strength <= $level_strength[$j]*10) {
        $user_info[$i]->level_name = $level_name[$j];
        $user_info[$i]->color = $level_color[$j];
        break;
      }
    }

    echo $user_mysql." <font color='".$user_info[$i]->color."'>"."中文"."</font>"." ".$user_info[$i]->strength."<br>";

    // 更新用户信息
    $sql="UPDATE users SET 
            level='".$user_info[$i]->level_name."',
            strength=".$user_info[$i]->strength.",
            color='".$user_info[$i]->color."',
            ZJU=".$user_info[$i]->ZJU.",
            HDU=".$user_info[$i]->HDU.",
            PKU=".$user_info[$i]->PKU.",
            UVA=".$user_info[$i]->UVA.",
            CF=".$user_info[$i]->CF." WHERE user_id='".$user_mysql."'";
    $result=mysql_query($sql);
  }

  // echo "update rank successfully!";

?>
