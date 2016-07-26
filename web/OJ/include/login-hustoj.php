<?php
  /**
   * This file is modified!
   * by yybird
   * @2016.06.17
  **/
?>

<?php 
  require_once("./include/my_func.inc.php");
//  require_once("./include/db_info.inc.php");


  function check_login($user_id, $password, $cid){
    global $LOGIN_DEFUNCT;
    $user_id=mysql_escape_string($user_id);
    $pass2 = 'No Saved';
    session_destroy();
    session_start();

    if (!is_numeric($cid)) {
      $canLogin = true;
      if ($LOGIN_DEFUNCT) {
        $sql = "SELECT * FROM privilege WHERE user_id='$user_id' AND defunct='N' AND rightstr!='teacher_assistant'";
        $result = mysql_query($sql);
        if (!mysql_num_rows($result))  $canLogin = false;
        mysql_free_result($result);
      }
      if ($canLogin) {
        $sql="INSERT INTO `loginlog` VALUES('$user_id','$pass2','".$_SERVER['REMOTE_ADDR']."',NOW())";
        @mysql_query($sql) or die(mysql_error());
        $sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
        if($row && pwCheck($password,$row['password'])){
          $user_id=$row['user_id'];
          mysql_free_result($result);
          return $user_id;
        }
        mysql_free_result($result);
      } else {
        return -1;
      }
    } else {
      $sql="SELECT `user_id`,`password`,contest_id FROM `team` WHERE `user_id`='$user_id' AND contest_id='$cid'";
      $result=mysql_query($sql);
      $row = mysql_fetch_array($result);
      if($row && pwCheck($password,$row['password'])){
        $user_id=$row['user_id'];
        $_SESSION['contest_id'] = $row['contest_id'];
        $pass = $row['password'];
        mysql_free_result($result);
        $sql="INSERT INTO `loginlog` VALUES('$user_id','$pass','".$_SERVER['REMOTE_ADDR']."',NOW())";
        @mysql_query($sql) or die(mysql_error());
        return $user_id;
      }
      mysql_free_result($result);
    }
    return false; 
  }
?>