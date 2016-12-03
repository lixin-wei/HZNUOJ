<?php
  /**
   * This file is modified!
   * by yybird
   * @2016.06.17
  **/
?>

<?php 
  require_once("my_func.inc.php");


  function check_login($user_id, $password, $cid){
    global $LOGIN_DEFUNCT;
    global $mysqli;
    $user_id=$mysqli->real_escape_string($user_id);
    $pass2 = 'No Saved';
  session_destroy();
    session_start();

    if (!is_numeric($cid)) {
      $canLogin = true;
      if ($LOGIN_DEFUNCT) {
        $sql = "SELECT * FROM privilege WHERE user_id='$user_id' AND defunct='N' AND rightstr!='teacher_assistant'";
        $result = $mysqli->query($sql);
        if (!$result->num_rows)  $canLogin = false;
        $result->free();
      }
      if ($canLogin) {
        $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','$pass2','".$_SERVER['REMOTE_ADDR']."',NOW())";
        @$mysqli->query($sql) or die($mysqli->error);
        $sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
        $result=$mysqli->query($sql);
        $row = $result->fetch_array();
        if($row && pwCheck($password,$row['password'])){
          $user_id=$row['user_id'];
          $result->free();
          return $user_id;
        }
        $result->free();
      } else {
        return -1;
      }
    } else {
      $sql="SELECT `user_id`,`password`,contest_id FROM `team` WHERE `user_id`='$user_id' AND contest_id='$cid'";
      $result=$mysqli->query($sql);
      $row = $result->fetch_array();
      if($row && pwCheck($password,$row['password'])){
        $user_id=$row['user_id'];
        $_SESSION['contest_id'] = $row['contest_id'];
        $pass = $row['password'];
        $result->free();
        $sql="INSERT INTO `loginlog`(user_id,password,ip,`time`) VALUES('$user_id','$pass','".$_SERVER['REMOTE_ADDR']."',NOW())";
        @$mysqli->query($sql) or die($mysqli->error);
        return $user_id;
      }
      $result->free();
    }
    return false; 
  }
?>