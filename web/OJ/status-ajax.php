<?php
  /**
   * This file is modified!
   * by yybird
   * @2015.07.02
  **/
?>

<?php
  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  ////////////////////////////Common head
  $cache_time=2;
  $OJ_CACHE_SHARE=false;
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  $view_title= "$MSG_STATUS";
  require_once("./include/const.inc.php");
  $solution_id=0;
  // check the top arg
  if (isset($_GET['solution_id'])){
    $solution_id=intval($_GET['solution_id']);
  }
  if($OJ_SIM){
    $sql="SELECT s.*,sim.*,u.`nick` FROM solution AS s LEFT JOIN `sim` AS sim ON s.solution_id=sim.s_id LEFT JOIN `users` AS u ON s.`user_id`=u.`user_id` WHERE s.solution_id='$solution_id' LIMIT 1";
  } else {
    $sql="SELECT s.*, u.`nick` FROM `solution` AS s LEFT JOIN `users` AS u ON s.`user_id`=u.`user_id` WHERE solution_id='$solution_id' LIMIT 1";
  }
  //echo $sql;
  $result = $mysqli->query($sql);// or die("Error! ".$mysqli->error);
  if ($result) {
    $row=$result->fetch_array();
    if(isset($_GET['tr']) && isset($_SESSION['user_id'])){
      $res=$row['result'];
      if ($res==11) {
        $sql="SELECT `error` FROM `compileinfo` WHERE `solution_id`='".$solution_id."'";
      } else {
        $sql="SELECT `error` FROM `runtimeinfo` WHERE `solution_id`='".$solution_id."'";
      }
      $result=$mysqli->query($sql);
      $row=$result->fetch_array();
      if($row){
        echo htmlentities(str_replace("\n\r","\n",$row['error']),ENT_QUOTES,"UTF-8");  
        $sql="DELETE FROM `custominput` WHERE `solution_id`='$solution_id'";
        $mysqli->query($sql);     
      }
      //echo $sql.$res;
    }else{
      if (isset($_GET['q']) && "user_id"==$_GET['q']) {
        echo $row['nick'] ? $row['user_id']."(".$row['nick'].")" : $row['user_id'];
      } else {
        $contest_id = $row['contest_id'];
        if ($contest_id>0) {
          $sql = "SELECT `title` FROM `contest` WHERE `contest_id`='$contest_id'";
				  $contest_title = $mysqli->query($sql)->fetch_array()[0];
          if (stripos($contest_title,$OJ_NOIP_KEYWORD)!==false) {
            echo "$OJ_NOIP_KEYWORD";
            exit(0);
          }
        }
        if (isset($_GET['t']) && "json"==$_GET['t']) {
          echo json_encode($row);
        } else {
          $http_judge_form = "";
          if(HAS_PRI("rejudge")) {
            $http_judge_form = "<form class='http_judge_form form-inline' style='display:none'><input type='hidden' name='sid' value='".$row['solution_id']."'>";
          }
          if($OJ_SIM && $row['sim']>=70 && $row['sim_s_id'] != $row['s_id']){
            if(HAS_PRI("see_compare")){
              $append = "<a href='comparesource.php?left=".$row['sim_s_id']."&right=".$row['solution_id']."'  class='am-btn am-btn-secondary am-btn-sm' >".$row['sim_s_id']."(".$row['sim']."%)</a>";
            } else {
              $append = "<span class='am-btn am-btn-secondary am-btn-sm'>".$row['sim_s_id']."(".$row['sim']."%)</span>";
            }
            if($row['sim_s_id']) $append .= "<span sid='".$row['sim_s_id']."' class='original'></span>";
            echo $row['result'].",".$row['memory']." KB,".$row['time']." ms,".$row['judger'].",".($row['pass_rate']*100).",".$row['sim_s_id'].",".$append.",".$http_judge_form;
          } else {
            echo $row['result'].",".$row['memory']." KB,".$row['time']." ms,".$row['judger'].",".($row['pass_rate']*100).",none,0,".$http_judge_form;
          }
        }
     }
   }
} else echo "0, 0, 0, unknown, 0, none, 0,";
?>