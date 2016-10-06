<?php
  /**
   * This file is modified
   * by yybird
   * @2016.04.26
  **/
?>

<?php
  $cache_time=10; 
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  require_once("./include/const.inc.php");
  require_once("./include/my_func.inc.php");
 
  // check user
  $user=$_GET['user'];
  if (!is_valid_user_name($user)){
    echo "No such User!";
    exit(0);
  }
 
  $view_title=$user ."@".$OJ_NAME;
  $user_mysql=$mysqli->real_escape_string($user);

  $sql="SELECT `school`,`email`,`nick`,level,color,strength,real_name,class,stu_id FROM `users` WHERE `user_id`='$user_mysql'";
  $result=$mysqli->query($sql);
  $row_cnt=$result->num_rows;
  if ($row_cnt==0){ 
    $view_errors= "No such User!";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }

  $row=$result->fetch_object();
  $school=$row->school;
  $email=$row->email;
  $nick=$row->nick;
  $real_name = $row->real_name;
  $stu_id=$row->stu_id;
  $class = $row->class;
            if ($class=="null") $class = "其它";
            if ($class=="cs151") $class = "计算机151";
            if ($class=="cs152") $class = "计算机152";
            if ($class=="cs153") $class = "计算机153";
            if ($class=="cs154") $class = "计算机154";
            if ($class=="se151") $class = "软件工程151";
            if ($class=="se152") $class = "软件工程152";
            if ($class=="iot151") $class = "物联网151";
            if ($class=="cs141") $class = "计算机141";
            if ($class=="cs142") $class = "计算机142";
            if ($class=="cs143") $class = "计算机143";
            if ($class=="cs144") $class = "计算机144";
            if ($class=="se141") $class = "软件工程141";
            if ($class=="se142") $class = "软件工程142";
            if ($class=="iot141") $class = "物联网141";
  $result->free();
 
  // 获取解题数大于10的用户数量存入user_cnt_divisor
  $sql = "SELECT user_id FROM users WHERE solved>10";
  $result  = $mysqli->query($sql) or die($mysqli->error);
  if($result) $user_cnt_divisor = $result->num_rows;
  else $user_cnt_divisor = 1;
  $result->free();
  //  echo $user_cnt_divisor;

  //get ac set and calculate strength
  $ac_set=array();
  $sql="SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND result=4 ORDER BY problem_id";
  $res=$mysqli->query($sql);
  while($pid=$res->fetch_array()[0]){
    $set_name=get_problemset($pid);
    if(!$ac_set[$set_name])$ac_set[$set_name]=array();
    array_push($ac_set[$set_name], $pid);
    
    //calculate strength
    $sql = "SELECT solved_user, submit_user FROM problem WHERE problem_id=".$pid;
    $y_result=$mysqli->query($sql);
    $y_row = $y_result->fetch_object();
    $solved = $y_row->solved_user;
    $submit = $y_row->submit_user;
    $scores = 100.0 * (1-($solved+$submit/2.0)/$user_cnt_divisor);
    if ($scores < 10) $scores = 10;
    $strength += $scores;
  }
  // count hznuoj solved
  $sql="SELECT count(DISTINCT problem_id) as ac FROM solution WHERE user_id='".$user_mysql."' AND result=4";
  $result=$mysqli->query($sql) or die($mysqli->error);
  $row=$result->fetch_object();
  $AC=$row->ac;
  $result->free();

  // count hznuoj submission
  $sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."'";
  $result=$mysqli->query($sql) or die($mysqli->error);
  $row=$result->fetch_object();
  $Submit=$row->Submit;
  $result->free();

  // 获取该用户AC的所有题目，存入result
  $sql = "SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND result=4";
  $result = $mysqli->query($sql) or die($mysqli->error);

  // 获取该用户AC的题目数量，存入rows_cnt
  if($result) $rows_cnt = $result->num_rows;
  else $rows_cnt = 0;

  $strength = 0;
  $level = "斗之气一段";
  $color = "#E0E0E0";
  /* 查找HZNUOJ未解决的题目编号 start */
  $hznu_unsolved_set = array();
  $sql = "SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND problem_id NOT IN (SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND result=4)";
  $result = $mysqli->query($sql);
  for ($i=0; $row=$result->fetch_array(); ++$i) {
    $hznu_unsolved_set[$i] = $row['problem_id'];
  }
  $result->free();
  /* 查找HZNUOJ未解决的题目编号 end */





  /* VJ计算部分 start */
  // 连接转入vjudge
  $mysqli->close();
  $mysqli = new mysqli($DB_VJHOST,$DB_VJUSER,$DB_VJPASS,"vhoj");
  if ($mysqli->connect_errno) die('Could not connect to the VJDB, Please contact administrator to fix this problem.' . $mysqli->error);
  $mysqli->query("set names utf8");

  // get AC number and problem ID from all OJs
  // ZOJ
  $sql = "SELECT DISTINCT C_ORIGIN_PROB, C_PROBLEM_ID FROM t_submission WHERE C_ORIGIN_OJ='ZOJ' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
  $result = $mysqli->query($sql) or die($mysqli->error);
  $ZJU = $result->num_rows;
  $zju_solved_set = array();
  $zju_vj_id = array(); // 映射vj上的ID
  for ($j=0; $j<$ZJU; $j++) {
    $row = $result->fetch_object();
    $zju_solved_set[] = $row->C_ORIGIN_PROB;
    $zju_vj_id[$row->C_ORIGIN_PROB] = $row->C_PROBLEM_ID;
  }
  $result->free();

  // HDOJ
  $sql = "SELECT DISTINCT C_ORIGIN_PROB, C_PROBLEM_ID FROM t_submission WHERE C_ORIGIN_OJ='HDU' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
  $result = $mysqli->query($sql) or die($mysqli->error);
  $HDU = $result->num_rows;
  $hdu_solved_set = array();
  $hdu_vj_id = array(); // 映射vj上的ID
  for ($j=0; $j<$HDU; $j++) {
    $row = $result->fetch_object();
    $hdu_solved_set[] = $row->C_ORIGIN_PROB;
    $hdu_vj_id[$row->C_ORIGIN_PROB] = $row->C_PROBLEM_ID;
  }
  $result->free();

  // POJ
  $sql = "SELECT DISTINCT C_ORIGIN_PROB, C_PROBLEM_ID FROM t_submission WHERE C_ORIGIN_OJ='POJ' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
  $result = $mysqli->query($sql) or die($mysqli->error);
  $PKU = $result->num_rows;
  $pku_solved_set = array();
  $pku_vj_id = array(); // 映射vj上的ID
  for ($j=0; $j<$PKU; $j++) {
    $row = $result->fetch_object();
    $pku_solved_set[] = $row->C_ORIGIN_PROB;
    $pku_vj_id[$row->C_ORIGIN_PROB] = $row->C_PROBLEM_ID;
  }
  $result->free();  

  // UVA
  $sql = "SELECT DISTINCT C_ORIGIN_PROB, C_PROBLEM_ID FROM t_submission WHERE C_ORIGIN_OJ='UVA' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
  $result = $mysqli->query($sql) or die($mysqli->error);
  $UVA = $result->num_rows;
  $uva_solved_set = array();
  $uva_vj_id = array(); // 映射vj上的ID
  for ($j=0; $j<$UVA; $j++) {
    $row = $result->fetch_object();
    $uva_solved_set[] = $row->C_ORIGIN_PROB;
    $uva_vj_id[$row->C_ORIGIN_PROB] = $row->C_PROBLEM_ID;
  }
  $result->free();  

  // Codeforces
  $sql = "SELECT DISTINCT C_ORIGIN_PROB, C_PROBLEM_ID FROM t_submission WHERE C_ORIGIN_OJ='CodeForces' AND C_STATUS='Accepted' AND C_USERNAME='".$user_mysql."'";
  $result = $mysqli->query($sql) or die($mysqli->error);
  $CF = $result->num_rows;
  $cf_solved_set = array();
  $cf_vj_id = array(); // 映射vj上的ID
  for ($j=0; $j<$CF; $j++) {
    $row = $result->fetch_object();
    $cf_solved_set[] = $row->C_ORIGIN_PROB;
    $cf_vj_id[$row->C_ORIGIN_PROB] = $row->C_PROBLEM_ID;
  }
  $result->free();  

  // 获取vjudge用户数存入user_cnt_divisor
  $sql = "SELECT C_USERNAME FROM t_user";
  $result  = $mysqli->query($sql) or die($mysqli->error);
  if($result) $user_cnt_divisor = $result->num_rows;
  else $user_cnt_divisor = 1;
  $result->free();
//  echo $user_cnt_divisor;

  // 获取该用户AC的所有题目，存入result
  $sql = "SELECT DISTINCT C_PROBLEM_ID FROM t_submission WHERE C_USERNAME='".$user_mysql."' AND C_STATUS='Accepted'";
  $result = $mysqli->query($sql) or die($mysqli->error);

  // 获取该用户AC的题目数量，存入rows_cnt
  if($result) $rows_cnt = $result->num_rows;
  else $rows_cnt = 0;

  // 对于每道AC的题目，计算其分数，并加至strength
  for ($j=0; $j<$rows_cnt; $j++) {
    // 获取题号
    $row = $result->fetch_object();
    $prob_id = $row->C_PROBLEM_ID;
    // 获取AC人数
    $sql = "SELECT COUNT(DISTINCT C_USER_ID) AS ac_user FROM t_submission WHERE C_PROBLEM_ID=".$prob_id." AND C_STATUS='Accepted'";
    $y_result = $mysqli->query($sql) or die($mysqli->error);
    $y_row = $y_result->fetch_object();
    $solved = $y_row->ac_user;
    // 获取提交人数
    $sql = "SELECT COUNT(DISTINCT C_USER_ID) AS sub_user FROM t_submission WHERE C_PROBLEM_ID=".$prob_id;
    $submit = $y_row->sub_user;
    $scores = 100.0 * (1-($solved+$submit/2.0)/$user_cnt_divisor);
    if ($scores < 10) $scores = 10;
    $strength += $scores;
    $y_result->free();
  }
  $result->free();
  /* VJ计算部分 start */




  // 连接转回hustoj
  $mysqli->close();
  $mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
  if ($mysqli->connect_errno) die('Could not connect: ' . $mysqli->error);
  $mysqli->query("set names utf8");

  require_once("./include/rank.inc.php");

  // 根据数组计算该实力对应的等级和颜色
  if ($strength > $max_strength) {
    $color = "#6C3365";
    $level = "斗战胜佛";
  } else for ($j=1; $j<$level_total; $j++) {

    if ($strength < $level_strength[$j]) {
      $level = $level_name[$j-1];
      $color = $level_color[$j-1];
      break;
    }
  }
  
  // 更新用户信息
  $sql="UPDATE users SET solved=".$AC.",submit=".$Submit.",level='".$level."',strength=".$strength.",color='".$color."',ZJU=".$ZJU.",HDU=".$HDU.",PKU=".$PKU.",UVA=".$UVA.",CF=".$CF." WHERE user_id='".$user_mysql."'";
  $result=$mysqli->query($sql);

  // 获取排名
  $sql="SELECT count(*) as `Rank` FROM `users` WHERE strength>".round($strength,2);
  $result=$mysqli->query($sql);
  $row=$result->fetch_array();
  $Rank=intval($row[0])+1;





  /* 计算图表相关信息 start */
  $total_ac = $AC+$CF+$HDU+$PKU+$UVA+$ZJU;
  $local_ac=$AC;
  // 计算总解题量的解题分
  $sql = "SELECT MAX(solved+CF+HDU+PKU+ZJU+UVA) FROM users";
  $result = $mysqli->query($sql);
  $row = $result->fetch_array();
  $max_solved = intval($row[0]);
  $solved_score = round(100.0*$total_ac/$max_solved); // 解题分
  $result->free();

  // 计算平均难度分
  $dif_score = round(1.0*$strength/$total_ac); 

  // 计算活跃度分
  $AC_day = 0; // A过题目的天数
  $sub_day = 0; // 交过题目的天数
  $sql = "SELECT * FROM solution WHERE user_id='$user_mysql' ORDER BY in_date";
  $result = $mysqli->query($sql);
  $last_AC_time = 0; // 上一次AC的时间
  $last_sub_time = 0; // 上一次提交的时间
  $offset = strtotime("2012-01-01"); // 设置一个参考时间，若距离此时间的天数相等，则为同一天，否则不是同一天
  $day_sec = 60*60*24; // 一天的秒数
  while ($row = $result->fetch_array()) {
    if (floor((strtotime($row['in_date'])-$offset)/$day_sec) != floor(($last_sub_time-$offset)/$day_sec)) { // 和上次提交不是同一天
      $sub_day++;
    }
    if ($row['result']==4) {
      if (floor((strtotime($row['in_date'])-$offset)/$day_sec) != floor(($last_AC_time-$offset)/$day_sec)) { // 计算有AC的天数
        $AC_day++;
      }
      $last_AC_time = strtotime($row['in_date']);
    }
    $last_sub_time = strtotime($row['in_date']);
  }
  $result->free();
  // 更新数据
  $sql = "SELECT * FROM users_cache WHERE user_id='$user_id'";
  $result = $mysqli->query($sql);
  $result_num = $result->num_rows;
  $result->free();
  if ($result_num) { // 如果表中已存在该user的信息，直接更新
    $sql = "UPDATE users_cache SET class='$stu->class', AC_day=$AC_day, sub_day=$sub_day WHERE user_id='$user_id'";
    $mysqli->query($sql);
  } else { // 否则插入
    $sql = "INSERT INTO users_cache(user_id, class, AC_day, sub_day) VALUES ('$user_id', '$stu->class', $AC_day, $sub_day)";
    $mysqli->query($sql);
  }
  // 查找最大活跃度
  $sql = "SELECT MAX(AC_day) AS max FROM users_cache";
  $result = $mysqli->query($sql);
  $row = $result->fetch_array();
  if ($row['max']) $act_score = round(100.0*$AC_day/$row['max']);
  else $act_score = 0;

  // 计算抄袭分
  // 获取该用户所有AC的提交
  $sql = "SELECT sim 
          FROM 
            sim RIGHT JOIN (
              SELECT solution_id
              FROM solution
              WHERE result=4 AND user_id='$user_mysql'
            ) AS s 
            ON sim.s_id=s.solution_id";
  $result = $mysqli->query($sql, $conn);
  $copy_sum = 0; // sim和
  $AC_num = $result->num_rows; // AC数
  // 逐个查看每个提交是否为抄袭
  while ($row = $result->fetch_array()) {
    $copy_sum += $row['sim'];
  }
  $result->free();
  if ($AC_num) $idp_score = 100-round(1.0*$copy_sum/$AC_num);
  else $idp_score = 0;

  // 计算总分
  $avg_score = round($solved_score*0.4+$dif_score*0.2+$act_score*0.2+$idp_score*0.2);
  /* 计算图表相关信息 end */





  if (HAS_PRI("see_hidden_user_info")){
    $sql="SELECT * FROM `loginlog` WHERE `user_id`='$user_mysql' order by `time` desc LIMIT 0,10";
    $result=$mysqli->query($sql) or die($mysqli->error);
    $view_userinfo=array();
    $i=0;
    for (;$row=$result->fetch_row();){
      $view_userinfo[$i]=$row;
      $i++;
    }
    echo "</table>";
    $result->free();
  }

  $sql="SELECT result,count(1) FROM solution WHERE `user_id`='$user_mysql' AND result>=4 group by result order by result";
  $result=$mysqli->query($sql);
  $view_userstat=array();
  $i=0;
  while($row=$result->fetch_array()){
    $view_userstat[$i++]=$row;
  }
  $result->free();

  $sql= "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql' group by md order by md desc";
  $result=$mysqli->query($sql);//$mysqli->real_escape_string($sql));
  $chart_data_all= array();
  //echo $sql;
    
  while ($row=$result->fetch_array()){
    $chart_data_all[$row['md']]=$row['c'];
  }
    
  $sql= "SELECT UNIX_TIMESTAMP(date(in_date))*1000 md,count(1) c FROM `solution` where  `user_id`='$user_mysql' and result=4 group by md order by md desc ";
  $result=$mysqli->query($sql);//$mysqli->real_escape_string($sql));
  $chart_data_ac= array();
  //echo $sql;
    
  while ($row=$result->fetch_array()){
    $chart_data_ac[$row['md']]=$row['c'];
  }
  
  $result->free();


  /* 获取HZNUOJ推荐题目的题目编号 start */
  $hznu_recommend_set = array();
  $sql = "SELECT DISTINCT problem_id FROM problem WHERE score<=$dif_score+5 AND score>=$dif_score-5 ORDER BY problem_id";
  $result = $mysqli->query($sql);
  for ($i=0; $row=$result->fetch_array(); ++$i) {
    $hznu_recommend_set[$i] = $row['problem_id'];
  }
  $result->free();
  /* 获取HZNUOJ推荐题目的题目编号 end */


  /////////////////////////Template
  require("template/".$OJ_TEMPLATE."/userinfo.php");
  /////////////////////////Common foot
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>

