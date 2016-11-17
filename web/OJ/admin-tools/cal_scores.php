<?php
  /**
   * This file is modified
   * by yybird
   * @2016.04.26
  **/
?>

<?php
  //ini_set("display_errors","On");
  require_once('../include/db_info.inc.php');
  require_once("../include/const.inc.php");
  require_once("../include/my_func.inc.php");
  $sql="SELECT user_id FROM users WHERE class='软工151' OR class='计算机154' ORDER BY class";
  $time_list=array("2015-11-15 21:30:00","2015-12-06 17:00:00","2015-12-27 20:45:00","2016-01-12 21:30:00");
  //echo $sql;
  $ress=$mysqli->query($sql);
  echo "用户名\t班级\t姓名\t学号";
  foreach ($time_list as $time) {
    echo "\t题量(0.4)-$time\t难度(0.2)-$time\t活跃(0.2)-$time\t独立(0.2)-$time\t总分-$time";
  }
  echo "\n";
  while($row=$ress->fetch_array()){
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
      //  echo $user_cnt_divisor;
      $strength = 0;
      $level = "斗之气一段";
      $color = "#E0E0E0";
      //get ac set and calculate strength
      $ac_set=array();
      $sql="SELECT DISTINCT problem_id FROM solution WHERE user_id='$user_mysql' AND result=4 AND in_date<='$time' ORDER BY problem_id";
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
        echo $user."\t".$class."\t".$real_name."\t".$stu_id;
      }
      echo "\t".$solved_score."\t".$dif_score."\t".$act_score."\t".$idp_score."\t".$avg_score;
    }
    echo "\n";
  }
  // check user

?>

