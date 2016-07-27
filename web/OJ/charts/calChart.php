<?php
  /**
   * This file is created
   * by yybird
   * @2016.04.02
   * last modified
   * by yybird
   * @2016.04.09
  **/
?>

<?php
  require_once("Student.php");
  require_once("DBTools.php");

  // 获取基本信息
  $userid = mysql_real_escape_string($_GET["user"]);
  $sql = "SELECT * FROM users WHERE user_id='$userid'";
  $result = mysql_query($sql);
  if (mysql_num_rows($result) == 0) { 
    echo "incorrect user id";
    exit();
  }
  $row = mysql_fetch_array($result);
  mysql_free_result($result);

  $stu = new Student();
  $stu->user_id = $userid;
  $stu->real_name = $row['real_name'];
  $stu->class = $row['class'];
  
  // 计算各项分数
  calMaxSol();
  $stu = calSol($stu, $row); // solved的相关计算必须在copy计算之前
  $stu = calSolByWeek($stu);
  $stu = calDif($stu, $row);
  $stu = calDifByWeek($stu);
  $stu = calEff($stu);
  $stu = calEffByWeek($stu);
  calMaxIns();
  $stu = calIns($stu);
  $stu = calInsByWeek($stu);
  calExtAct();
  $stu = calAct($stu);
  $stu = calActByWeek($stu);
  $stu = calCopy($stu); // 必须先计算完solved再计算抄袭率
  $stu = calCopyByWeek($stu);

  // 总分为六边形面积
  $stu->total_score = ($stu->solved_score*$stu->dif_score 
                    + $stu->dif_score*$stu->eff_score 
                    + $stu->eff_score*$stu->insist_score
                    + $stu->insist_score*$stu->act_score
                    + $stu->act_score*$stu->idp_score
                    + $stu->idp_score*$stu->solved_score) / 600.0;

?>