<?php
  /**
   * This file is modified
   * by yybird
   * @2016.04.26
  **/
?>
<link rel="stylesheet" href="/OJ/plugins/AmazeUI/css/amazeui.min.css"/>
<?php
  //ini_set("display_errors","On");
  require_once('../include/db_info.inc.php');
  require_once("../include/const.inc.php");
  require_once("../include/my_func.inc.php");
  $sql="SELECT user_id FROM users WHERE class='软工151' OR class='计算机154' ORDER BY class";
  $time_list=array("2015-11-15 21:30:00","2015-12-06 17:00:00","2015-12-27 20:45:00","2016-01-12 21:30:00");
  //echo $sql;
  $ress=$mysqli->query($sql);
  echo "<table class='am-table am-table-bordered am-table-striped' style='word-break:keep-all;'>";
  echo "<tr>";
  echo "<th>用户名</th><th>班级</th><th>姓名</th><th>学号</th>";
  foreach ($time_list as $time) {
    echo "<th>题数</th><th>题量(0.4)-$time</th><th>难度(0.2)-$time</th><th>活跃(0.2)-$time</th><th>独立(0.2)-$time</th><th>总分-$time</th>";
  }
  echo "</tr>";
  while($row=$ress->fetch_array()){
    echo "<tr>";
    $user=$row['user_id'];
    $first=1;
    foreach ($time_list as $time) {

      $user_mysql=$mysqli->real_escape_string($user);

      $sql="SELECT `school`,`email`,`nick`,level,color,strength,real_name,class,stu_id FROM `users` WHERE `user_id`='$user_mysql'";
      $result=$mysqli->query($sql);
      $row_cnt=$result->num_rows;

      $row=$result->fetch_object();
      $school=$row->school;
      $email=$row->email;
      $nick=$row->nick;
      $real_name = $row->real_name;
      $stu_id=$row->stu_id;
      $class = $row->class;
      $result->free(); 
     
      // 获取解题数大于10的用户数量存入user_cnt_divisor
      $sql = "SELECT user_id FROM users WHERE solved>10";
      $result  = $mysqli->query($sql) or die($mysqli->error);
      if($result) $user_cnt_divisor = $result->num_rows;
      else $user_cnt_divisor = 1;
      $result->free();
      //get ac set and calculate strength
      $ac_set=array();
      $sql="SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND result=4 AND in_date<='$time' ORDER BY problem_id";
      $res=$mysqli->query($sql);
      while($pid=$res->fetch_array()[0]){
        $set_name=get_problemset($pid);
        if(!$ac_set[$set_name])$ac_set[$set_name]=array();
        array_push($ac_set[$set_name], $pid);
      }
      // count hznuoj solved
      $sql="SELECT count(DISTINCT problem_id) as ac FROM solution WHERE user_id='".$user_mysql."' AND result=4  AND in_date<='$time' ";
      $result=$mysqli->query($sql) or die($mysqli->error);
      $row=$result->fetch_object();
      $AC=$row->ac;
      $result->free();

      // count hznuoj submission
      $sql="SELECT count(solution_id) as `Submit` FROM `solution` WHERE `user_id`='".$user_mysql."'  AND in_date<='$time' ";
      $result=$mysqli->query($sql) or die($mysqli->error);
      $row=$result->fetch_object();
      $Submit=$row->Submit;
      $result->free();

      // 获取该用户AC的所有题目，存入result
      $sql = "SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND result=4  AND in_date<='$time' ";
      $result = $mysqli->query($sql) or die($mysqli->error);

      // 获取该用户AC的题目数量，存入rows_cnt
      if($result) $rows_cnt = $result->num_rows;
      else $rows_cnt = 0;

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
      $sql = "SELECT * FROM solution WHERE user_id='$user_mysql' ORDER BY in_date  AND in_date<='$time' ";
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
      $sql="UPDATE users_cache SET AC_day=$AC_day WHERE user_id='$user'";
      $mysqli->query($sql);
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
                  WHERE result=4 AND user_id='$user_mysql' AND in_date<='$time' 
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
      if($first){
        $first=0;
        echo "<td>".$user."</td><td>".$class."</td><td>".$real_name."</td><td>".$stu_id."</td>";
      }
      echo "<td>".$total_ac."</td><td>".$solved_score."</td><td>".$dif_score."</td><td>".$act_score."</td><td>".$idp_score."</td><td>".$avg_score."</td>";
    }
    echo "</tr>";
  }
  // check user
  echo "</table>";
?>

