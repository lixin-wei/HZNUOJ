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

  class Student {
    var $user_id;
    var $real_name;
    var $class;
    var $HZNU;
    var $total_solved;
    var $solved_score;
    var $avg_dif; // average difficult
    var $dif_score; // 1
    var $copy_per; // copy persentage
    var $idp_score; // 2 independence score
    var $hw_rank; // homework rank
    var $act_score; // 3 activity score
    var $time_eff; // time efficient
    var $mem_eff; // memory efficient
    var $eff_score; // 4
    var $insist_day;
    var $insist_score; // 5
    var $total_score;

    // 图表中的各数据
    var $solved_weekly = array(); // 每周的解题量
    var $solved_rank_weekly = array(); // 每周的解题量排名
    var $dif_weekly = array(); // 每周的解题难度
    var $time_weekly = array(); // 每周的时间效率
    var $mem_weekly = array(); // 每周的空间效率
    var $insist_day_weekly = array(); // 每周的坚持天数
    var $act_weekly = array(); // 每周的积极性
    var $copy1_weekly = array(); // 每周的抄袭数
    var $copy2_weekly = array(); // 每周的被抄袭数
    
    // 图表中的周分数
    var $solved_score_week; // 本周的解题分
    var $dif_score_week; // 本周的难度分
    var $eff_score_week; // 本周的代码效率分
    var $insist_score_week; // 本周的坚持性分
    var $act_score_week; // 本周的积极性分
    var $idp_score_week; // 本周的独立性分
  }


  function getAllStu() { // 获取所有学生信息前需要先更新下所有学生信息

    global $conn;
    global $classArr;

    $sql = "SELECT * FROM users WHERE class='$classArr[0]'";
    for ($i=1; $i<count($classArr); ++$i)
      $sql .= "OR class='$classArr[$i]'";
    $sql .= "ORDER BY class, user_id";
    $result = mysql_query($sql, $conn);
    
    // 计算学生信息
    $stu = array();
    calMaxSol();
    calMaxIns();
    calExtAct();

    for ($i=0; $row=mysql_fetch_array($result); ++$i) {
      
      $stu[$i] = new Student();

      // 获取基本信息
      $stu[$i]->user_id = $row['user_id'];
      $stu[$i]->real_name = $row['real_name'];
      $stu[$i]->class = classToCN($row['class']);

      // 计算解题数
      $stu[$i] = calSol($stu[$i], $row);
      $stu[$i] = calSolByWeek($stu);
      
      // 计算平均解题难度
      $stu[$i] = calDif($stu[$i]);
      $stu[$i] = calDifByWeek($stu[$i]);

      // 计算代码效率
      $stu[$i] = calEff($stu[$i]);
      $stu[$i] = calEffByWeek($stu[$i]);

      // 计算坚持天数
      $stu[$i] = calIns($stu[$i]);
      $stu[$i] = calInsByWeek($stu[$i]);

      // 计算积极性
      $stu[$i] = calAct($stu[$i]);
      $stu[$i] = calActByWeek($stu[$i]);

      // 计算抄袭率
      $stu[$i] = calCopy($stu[$i]);
      $stu[$i] = calCopyByWeek($stu[$i]);

    }

    return $stu;
  }
?>