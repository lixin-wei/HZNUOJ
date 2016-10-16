<?php
  /**
   * This file is created
   * by yybird
   * @2016.01.13
   * last modified
   * by yybird
   * @2016.04.09
  **/
?>


<?php
  require_once "../include/db_info.inc.php";
  // echo "Connect Start!<br/>";
  $classArr = array("cs151","cs152","cs153","cs154","se151","se152","se153","iot151","iot152"); // 班级数组
  $contestArr = array(1032,1033,1034,1035,1037,1038); // 要算入的contest id

  // 教学开始周和周数
  $week_start = strtotime("2015-09-07");
  static $week_num = 16;
  static $week_sec = 604800; // 一周的秒数
  static $full_mark_solved = 100.0; // 解题量的满分值
  static $full_mark_dif = 100.0; // 解题难度的满分值
  static $full_mark_eff = 100.0; // 代码效率的满分值
  static $full_mark_ins = 100.0; // 坚持天数的满分值
  static $full_mark_act = 100.0; // 积极性的满分值
  static $full_mark_idp = 100.0; // 独立性的满分值

  // 班级名称转中文
  function classToCN($cls) {
    if ($cls == "se151") return "软件工程151";
    if ($cls == "se152") return "软件工程152";
    if ($cls == "cs151") return "计算机151";
    if ($cls == "cs152") return "计算机152";
    if ($cls == "cs153") return "计算机153";
    if ($cls == "cs154") return "计算机154";
    if ($cls == "iot151") return "物联网151";
    if ($cls == "iot152") return "物联网152";
    return $cls;
  }

  // 各算法
  function solAlg($solved, $max_solved) { // solved score algorithm
    global $full_mark_solved;

    $score = 1.0 * $full_mark_solved * $solved / $max_solved;

    return $score;
  }
  function difAlg($avg_dif, $solved) {
    global $full_mark_dif;

    $score = $full_mark_dif * $avg_dif / 80.0;
    $score += 0.0005 * $full_mark_dif * $solved;
    if ($score >= $full_mark_dif) $score = $full_mark_dif;
    else $score = round($score, 3);

    return $score;
  }
  function effAlg() {
    // 因为周算法与总算法暂不相同，故此处先空着
  }
  function insAlg($insist_day, $max_insist) {
    global $full_mark_ins;

    $score = round(1.0*$full_mark_ins*$insist_day/$max_insist, 3);

    return $score;
  }
  function actAlg($act, $max_act, $min_act) {
    global $full_mark_act;

    $score = 1.0 * $full_mark_act * ($max_act-$act) / ($max_act-$min_act);

    return $score;
  }
  function copyAlg($AC_sum, $copy_sum, $HZNU) { // 分别为AC数，sim和，解题量
    global $full_mark_idp;

    // 计算抄袭百分比
    $AC_sum *= 100; // 转化为sim值
    $copy_per = 1;
    $score = 0;
    if ($AC_sum != 0) {
      $copy_per = round(1.0*$copy_sum/$AC_sum, 3);

      // 下面一段为计算分数2前的调整优化
      // 设置抄袭率参数
      $para = 0;
      if ($copy_per > 0.15) $para += 0.25*$full_mark_idp*($copy_per-0.15);
      if ($copy_per > 0.25) $para += 0.5*$full_mark_idp*($copy_per-0.25);
      if ($copy_per > 0.35) $para += 0.75*$full_mark_idp*($copy_per-0.35);
      if ($copy_per > 0.45) $para += 1.0*$full_mark_idp*($copy_per-0.45);
      if ($copy_per > 0.55) $para += 1.25*$full_mark_idp*($copy_per-0.55);
      if ($copy_per > 0.65) $para += 1.5*$full_mark_idp*($copy_per-0.65);
      if ($copy_per > 0.75) $para += 1.75*$full_mark_idp*($copy_per-0.75);
      if ($copy_per > 0.85) $para += 2*$full_mark_idp*($copy_per-0.75);

      // 每5题可以抵一次抄袭提交
      $copy_sum -= $HZNU*20;
      if ($copy_sum < 0) {
        $para += 1.0*$copy_sum/$AC_sum;
        if ($para < 0) $para = 0;
        $copy_sum = 0;
      }

      // 得出分数2
      $score = round($full_mark_idp*(1-1.0*$copy_sum/$AC_sum)-$para, 3);
      if ($score < 0) $score = 0;
    }

    return $score;
  }


  // 计算解题量
  $max_total_solved = 0;
  function calMaxSol() { // 计算当前年级最高解题数
    global $conn;
    global $classArr;
    global $max_total_solved;

    $sql = "SELECT MAX(solved+CF+HDU+PKU+UVA+ZJU) AS max FROM users WHERE class='$classArr[0]'";
    for ($i=1; $i<count($classArr); ++$i)
      $sql .= "OR class='$classArr[$i]'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $max_total_solved = $row['max'];
    $result->free();
  }

  function calSol($stu, $row) { // 计算该用户的解题数
    global $max_total_solved;

    $stu->HZNU = $row['solved'];
    $stu->total_solved = $stu->HZNU + $row['ZJU'] + $row['HDU'] + $row['PKU'] + $row['UVA'] + $row['CF'];
    $stu->solved_score = solAlg($stu->total_solved, $max_total_solved);
    
    return $stu;
  }

  function calSolByWeek($stu) { // 计算每周的解题数
    global $conn;
    global $week_start;
    global $week_num;
    global $week_sec;
    global $full_mark_solved;
    $user_id = $stu->user_id;

    $ithWeek = (time()-$week_start) / $week_sec + 1; // 当前是第几周
    if ($ithWeek > $week_num) $ithWeek = $week_num; // 判断是否超过教学周总数
  
    // 计算每周的数据
    for ($i=1; $i<=$ithWeek; ++$i) {
      $start_time = $week_start+($i-1)*$week_sec;
      $end_time = $week_start+$i*$week_sec;
      
      // 计算每周的解题量
      $sql = "SELECT COUNT(DISTINCT problem_id) AS sum FROM solution WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>=$start_time AND UNIX_TIMESTAMP(in_date)<$end_time";
      $result = $mysqli->query($sql);
      $row = $result->fetch_array();
      $stu->solved_weekly[$i] = $row['sum'];
      $solved_week = $row['sum']; // 本周的解题量，方便接下来SQL语句中使用
      $result->free();

      // 更新数据至缓存
      $sql = "SELECT * FROM users_cache_array WHERE user_id='$user_id' AND type='solved' AND week='$i'";
      $result = $mysqli->query($sql);
      $result_num = $result->num_rows;
      $result->free();
      if ($result_num) { // 存在则更新
        $sql = "UPDATE users_cache_array SET value_int='$solved_week' WHERE user_id='$user_id' AND type='solved' AND week='$i'";
        $mysqli->query($sql);
      } else { // 否则插入
        $sql = "INSERT INTO users_cache_array(user_id, type, week, value_int) VALUES ('$user_id', 'solved', '$i', '$solved_week')";
        $mysqli->query($sql);
      }

      // 计算每周排名
      $sql = "SELECT COUNT(*) AS rank FROM users_cache_array WHERE type='solved' AND week='$i' AND value_int>'$solved_week'";
      $result = $mysqli->query($sql);
      $row = $result->fetch_array();
      $stu->solved_rank_weekly[$i] = $row['rank']+1;

      if ($i == $ithWeek) { // 计算本周分数
        $sql = "SELECT MAX(value_int) AS max FROM users_cache_array WHERE type='solved' AND week='$i'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_array();
        $stu->solved_score_week = solAlg($stu->solved_weekly[$i], $row['max']);
      }
    }

    return $stu;
  }


  // 计算解题难度
  function calDif($stu, $row) {
    $full_mark = 100.0;

    $stu->avg_dif = round(1.0*$row['strength']/$stu->total_solved, 2);
    $stu->dif_score = difAlg($stu->avg_dif, $stu->total_solved);

    return $stu;
  }

  function calDifByWeek($stu) { // 计算每周做的题目的难度
    global $conn;
    global $week_start;
    global $week_num;
    global $week_sec;
    $user_id = $stu->user_id;

    $ithWeek = (time()-$week_start) / $week_sec + 1; // 当前是第几周
    if ($ithWeek > $week_num) $ithWeek = $week_num; // 判断是否超过教学周总数

    // 计算每周的解题难度
    $num_rows = 0;
    $total_score = 0;
    for ($i=1; $i<=$ithWeek; ++$i) {
      $start_time = $week_start+($i-1)*$week_sec;
      $end_time = $week_start+$i*$week_sec;
      $sql = "SELECT score 
              FROM 
                problem RIGHT JOIN (
                  SELECT DISTINCT problem_id AS pid 
                  FROM solution 
                  WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time'
                ) AS s
                ON problem.problem_id=s.pid";
      $result = $mysqli->query($sql);
      $num_rows += $result->num_rows;
      while ($row = $result->fetch_array()) {
        $total_score += $row['score'];
      }
      if ($num_rows) $stu->dif_weekly[$i] = round(1.0*$total_score/$num_rows, 3);
      else $stu->dif_weekly[$i] = 0;
      $result->free();

      if ($i == $ithWeek) { // 计算本周分数
        $stu->dif_score_week = difAlg($stu->dif_weekly[$i], $stu->solved_weekly[$i]);
      }
    }

    return $stu;
  }


  // 计算代码效率
  function calEff($stu) {
    global $conn;
    $full_mark = 100.0; // 满分值
    $user_id = $stu->user_id;
    $prob_start = 1900;
    $prob_end = 2014;
    $prob_total = $prob_end - $prob_start + 1;

    $sql = "SELECT SUM(time), SUM(memory), COUNT(problem_id), COUNT(DISTINCT problem_id)
        FROM solution 
        WHERE result=4 AND user_id='$user_id' AND problem_id<='$prob_end' AND problem_id>='$prob_start'";
    $result = $mysqli->query($sql, $conn);
    $row = $result->fetch_array();
    $result->free();
    
    $AC_sum = $row['COUNT(problem_id)'];
    $prob_sum = $row['COUNT(DISTINCT problem_id)'];
    $time_sum = $row['SUM(time)'];
    $mem_sum = $row['SUM(memory)'];

    // 算出平均效率（一定要算这个，因为一个人可能多次AC同一道题）
    $time_avg = 1.0 * $time_sum / $AC_sum;
    $mem_avg = 1.0 * $mem_sum / $AC_sum;

    // 加权
    $time_sum = $time_avg*$prob_sum + ($prob_total-$prob_sum)*1000;
    $mem_sum = $mem_avg*$prob_sum + ($prob_total-$prob_sum)*1024*64;

    $stu->time_eff = 1.0 * $time_sum / $prob_total;
    $stu->mem_eff = 1.0 * $mem_sum / $prob_total;

    $stu->eff_score = $full_mark - ($full_mark/2.0*$stu->time_eff/1000.0 + $full_mark/2.0*$stu->mem_eff/64.0/1024.0);
    $stu->eff_score = round($stu->eff_score, 3);

    return $stu;
  }

  function calEffByWeek($stu) { // 计算每周时间和空间效率
    global $conn;
    global $week_start;
    global $week_num;
    global $week_sec;
    global $full_mark_eff;
    $user_id = $stu->user_id;

    $ithWeek = (time()-$week_start) / $week_sec + 1; // 当前是第几周
    if ($ithWeek > $week_num) $ithWeek = $week_num; // 判断是否超过教学周总数

    // 计算每周的时空效率
    $num_rows = 0;
    $total_time = 0;
    $total_mem = 0;
    for ($i=1; $i<=$ithWeek; ++$i) {
      $start_time = $week_start+($i-1)*$week_sec;
      $end_time = $week_start+$i*$week_sec;
      $sql = "SELECT * FROM solution WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time'";
      $result = $mysqli->query($sql);
      $num_rows += $result->num_rows;
      while ($row = $result->fetch_array()) {
        $total_time += $row['time'];
        $total_mem += $row['memory'];
      }
      if ($num_rows) {
        $stu->time_weekly[$i] = round(1.0*$total_time/$num_rows, 3);
        $stu->mem_weekly[$i] = round(1.0*$total_mem/$num_rows, 3);
      } else {
        $stu->time_weekly[$i] = 0;
        $stu->mem_weekly[$i] = 0;
      }
      $result->free();

      // 计算每周时空复杂度得分（周算法与总算法不相同，周算法进行了简化）
      if ($i == $ithWeek) {
        $stu->eff_score_week = 100.0 * (1-($stu->time_weekly[$i]/1000.0+$stu->mem_weekly[$i]/(64*1024)));
      }
    }

    return $stu;
  }


  // 计算坚持天数
  $max_insist_day = 0;
  function calMaxIns() {
    global $conn;
    global $classArr;
    global $max_insist_day;

    $sql = "SELECT MAX(AC_day) AS max FROM users_cache WHERE class='$classArr[0]'";
    for ($i=1; $i<count($classArr); ++$i)
      $sql .= "OR class='$classArr[$i]'";
    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $max_insist_day = $row['max'];
    $result->free();
  }

  function calIns($stu) {
    global $conn;
    global $max_insist_day;
    $full_mark = 100.0;
    $user_id = $stu->user_id;
    $AC_day = 0; // A过题目的天数
    $sub_day = 0; // 交过题目的天数

    // 计算有AC的天数
    $sql = "SELECT * FROM solution WHERE user_id='$user_id' ORDER BY in_date";
    $result = $mysqli->query($sql, $conn);
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

    $stu->insist_day = $AC_day;
    $stu->insist_score = insAlg($stu->insist_day, $max_insist_day);

    return $stu;
  }

  function calInsByWeek($stu) { // 计算每周坚持天数
    global $conn;
    global $week_start;
    global $week_num;
    global $week_sec;
    $user_id = $stu->user_id;

    $ithWeek = (time()-$week_start) / $week_sec + 1; // 当前是第几周
    if ($ithWeek > $week_num) $ithWeek = $week_num; // 判断是否超过教学周总数

    // 计算每周的数据
    for ($i=1; $i<=$ithWeek; ++$i) {
      $start_time = $week_start+($i-1)*$week_sec;
      $end_time = $week_start+$i*$week_sec;

      // 计算每周的坚持天数
      $sql = "SELECT * FROM solution WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time' ORDER BY in_date";
      $result = $mysqli->query($sql);
      $last_AC_time = 0; // 上一次AC的时间
      $AC_day = 0;
      $offset = strtotime("2012-01-01"); // 设置一个参考时间，若距离此时间的天数相等，则为同一天，否则不是同一天
      $day_sec = 86400; // 一天的秒数
      while ($row = $result->fetch_array()) {     
        if (floor((strtotime($row['in_date'])-$offset)/$day_sec) != floor(($last_AC_time-$offset)/$day_sec)) { // 两者不在同一天
          $AC_day++;
        }
        $last_AC_time = strtotime($row['in_date']);
      }
      $stu->insist_day_weekly[$i] = $AC_day;
      $result->free();

      // 更新坚持天数
      $sql = "SELECT * FROM users_cache_array WHERE user_id='$user_id' AND type='insist' AND week='$i'";
      $result = $mysqli->query($sql);
      $result_num = $result->num_rows;
      $result->free();
      if ($result_num) {
        $sql = "UPDATE users_cache_array SET value_int='$AC_day' WHERE user_id='$user_id' AND type='insist' AND week='$i'";
        $mysqli->query($sql);
      } else {
        $sql = "INSERT INTO users_cache_array(user_id, type, week, value_int) VALUES ('$user_id', 'insist', '$i', '$AC_day')";
        $mysqli->query($sql);
      }

      if ($i == $ithWeek) {
        $sql = "SELECT MAX(value_int) AS max FROM users_cache_array WHERE type='insist' AND week='$i'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_array();
        $stu->insist_score_week = insAlg($stu->insist_day_weekly[$i] ,$row['max']);
      }
    }

    return $stu;
  }


  // 计算积极性
  $max_act_value = 0;
  $min_act_value = 1000000000;
  function calExtAct() {
    global $conn;
    global $max_act_value;
    global $min_act_value;
    global $classArr;

    $sql = "SELECT MAX(activity) AS max, MIN(activity) AS min FROM users_cache WHERE class='$classArr[0]'";
    for ($i=1; $i<count($classArr); ++$i)
      $sql .= "OR class='$classArr[$i]'";

    $result = $mysqli->query($sql);
    $row = $result->fetch_array();
    $max_act_value = $row['max'];
    $min_act_value = $row['min'];
    $result->free();
  }

  function calAct($stu) {
    global $conn;
    global $max_act_value;
    global $min_act_value;
    global $contestArr;
    $user_id = $stu->user_id;

    // 计算activity
    $activity = 0;
    $row_num = 0;
    for ($i=0; $i<count($contestArr); ++$i) {
      $sql = "SELECT * FROM solution WHERE contest_id=$contestArr[$i] AND result=4 AND user_id='$user_id'";
      $result = $mysqli->query($sql);
      $row_num += $result->num_rows;
      while ($row = $result->fetch_array()) {
        $activity += strtotime($row['in_date']);
      }
      $result->free();
    }
    if ($row_num) $activity = 1.0 * $activity / $row_num;
    else $activity = strtotime("now");

    // 更新activity
    $sql = "SELECT * FROM users_cache WHERE user_id='$user_id'";
    $result = $mysqli->query($sql);
    $result_num = $result->num_rows;
    $result->free();
    if ($result_num) { // 若存在，则更新
      $sql = "UPDATE users_cache SET class='$stu->class', activity=$activity WHERE user_id='$user_id'";
      $mysqli->query($sql);
    } else { // 若不存在，则插入
      $sql = "INSERT INTO users_cache(user_id, class, activity) VALUES ('$user_id', '$stu->class', $activity)";
      $mysqli->query($sql);
    }

    // 计算分数
    $stu->act_score = actAlg($activity, $max_act_value, $min_act_value);

    return $stu;
  }

  /*
   * 计算每周积极性处有个小bug，若学生提前完成作业，后面几周的积极性还会一直为0
   */
  function calActByWeek($stu) { // 计算每周积极性
    global $conn;
    global $week_start;
    global $week_num;
    global $week_sec;
    $contestArr = array(1032,1033,1034,1035,1037,1038); // 要算入的contest id
    $user_id = $stu->user_id;

    $ithWeek = (time()-$week_start) / $week_sec + 1; // 当前是第几周
    if ($ithWeek > $week_num) $ithWeek = $week_num; // 判断是否超过教学周总数

    // 计算每周的积极性
    for ($i=1; $i<=$ithWeek; ++$i) {
      $start_time = $week_start+($i-1)*$week_sec;
      $end_time = $week_start+$i*$week_sec;

      // 计算该周的积极性
      $activity = 0;
      $row_num = 0;
      for ($j=0; $j<count($contestArr); ++$j) {
        $sql = "SELECT * FROM solution WHERE user_id='$user_id' AND contest_id='$contestArr[$j]' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time'";
        $result = $mysqli->query($sql);
        $row_num += $result->num_rows;
        while ($row = $result->fetch_array()) {
          $activity += strtotime($row['in_date']);
        }
        $result->free();
      }
      if ($row_num) $activity = 1.0 * $activity / $row_num;
      else $activity = strtotime("now");
      $stu->act_weekly[$i] = $activity;

      // 更新该周的积极性
      $sql = "SELECT * FROM users_cache_array WHERE user_id='$user_id' AND type='activity' AND week='$i'";
      $result = $mysqli->query($sql);
      $result_num = $result->num_rows;
      $result->free();
      if ($result_num) { // 若存在，则更新
        $sql = "UPDATE users_cache_array SET value_int='$activity' WHERE user_id='$user_id' AND type='activity' AND week='$i'";
        $mysqli->query($sql);
      } else { // 若不存在，则插入
        $sql = "INSERT INTO users_cache_array(user_id, type, week, value_int) VALUES ('$user_id', 'activity', '$i', '$activity')";
        $mysqli->query($sql);
      }

      // 计算积极性排名
      $sql = "SELECT COUNT(*) AS rank FROM users_cache_array WHERE type='activity' AND week='$i' AND value_int<'$activity'";
      $result = $mysqli->query($sql);
      $row = $result->fetch_array();
      $stu->act_rank_weekly[$i] = $row['rank']+1;

      // 计算本周分数
      if ($i == $ithWeek) {
        $sql = "SELECT MAX(value_int) AS max, MIN(value_int) AS min FROM users_cache_array WHERE type='activity' AND week='$i'";
        $result = $mysqli->query($sql);
        $row = $result->fetch_array();
        $stu->act_score_week = actAlg($activity, $row['max'], $row['min']);
      }
    }

    return $stu;
  }


  // 计算抄袭率
  function calCopy($stu) {
    global $conn;
    $full_mark = 100.0; // 设置满分值
    $user_id = $stu->user_id;

    // 获取该用户所有AC的提交
    $sql = "SELECT sim 
            FROM 
              sim RIGHT JOIN (
                SELECT solution_id
                FROM solution
                WHERE result=4 AND user_id='$user_id'
              ) AS s 
              ON sim.s_id=s.solution_id";
    $result = $mysqli->query($sql, $conn);
    $copy_sum = 0; // sim和
    $AC_sum = $result->num_rows; // AC数

    // 逐个查看每个提交是否为抄袭
    while ($row = $result->fetch_array()) {
      $copy_sum += $row['sim'];
    }
    $result->free();

    $stu->idp_score = copyAlg($AC_sum, $copy_sum, $stu->HZNU);

    return $stu;
  }

  function calCopyByWeek($stu) { // 计算每周抄袭情况
    global $conn;
    global $week_start;
    global $week_num;
    global $week_sec;
    $user_id = $stu->user_id;

    $ithWeek = (time()-$week_start) / $week_sec + 1; // 当前是第几周
    if ($ithWeek > $week_num) $ithWeek = $week_num; // 判断是否超过教学周总数

    // 计算每周的数据
    for ($i=1; $i<=$ithWeek; ++$i) {
      $start_time = $week_start+($i-1)*$week_sec;
      $end_time = $week_start+$i*$week_sec;

      // 计算抄袭他人的数量
      $sql = "SELECT COUNT(*) AS sum 
              FROM 
                sim INNER JOIN (
                  SELECT solution_id
                  FROM solution
                  WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time'
                ) AS s
                ON sim.s_id=s.solution_id";
      $result = $mysqli->query($sql);
      $row = $result->fetch_array();
      $stu->copy1_weekly[$i] = $row['sum'];
      $result->free();

      // 计算抄袭他人的数量
      $sql = "SELECT COUNT(*) AS sum 
              FROM 
                sim INNER JOIN (
                  SELECT solution_id
                  FROM solution
                  WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time'
                ) AS s
                ON sim.sim_s_id=s.solution_id";
      $result = $mysqli->query($sql);
      $row = $result->fetch_array();
      $stu->copy2_weekly[$i] = $row['sum'];
      $result->free();

      // 计算每周的抄袭分数
      if ($i == $ithWeek) {
        $sql = "SELECT sim 
                FROM 
                  sim RIGHT JOIN (
                    SELECT solution_id
                    FROM solution
                    WHERE user_id='$user_id' AND result=4 AND UNIX_TIMESTAMP(in_date)>='$start_time' AND UNIX_TIMESTAMP(in_date)<'$end_time'
                  ) AS s
                  ON sim.s_id=s.solution_id";
        $result = $mysqli->query($sql);
        $copy_sum = 0; // sim和
        $AC_sum = $result->num_rows; // AC数
        while ($row = $result->fetch_array()) {     
          $copy_sum += $row['sim'];
        }
        $result->free();

        $stu->idp_score_week = copyAlg($AC_sum, $copy_sum, $stu->solved_weekly[$i]); // 抄袭分数
      }
    }

    return $stu;
  }

?>