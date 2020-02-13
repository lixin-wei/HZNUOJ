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
	  $checkResult = false ;
    $cid=$mysqli->real_escape_string($cid);
    session_destroy();
    session_start();
    $ip = ($_SERVER['REMOTE_ADDR']);
	if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
	    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
	    $tmp_ip=explode(',',$REMOTE_ADDR);
	    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
	}
  if(!is_numeric($cid)) {
	    $canLogin = true;
      if ($LOGIN_DEFUNCT) {
        $sql = "SELECT * FROM privilege WHERE user_id='$user_id' AND defunct='N' AND rightstr!='teacher_assistant'";
        $result = $mysqli->query($sql);
        if (!$result->num_rows)  $canLogin = false;
        $result->free();
      }
      if ($canLogin) {
        $sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='$user_id' and defunct='N' ";
	      $result = $mysqli->query($sql);
        $row = $result->fetch_array();
        if($row && pwCheck($password,$row['password'])){
          $user_id=$row['user_id'];
          $sql="update users set accesstime=now() where user_id='$user_id'";
          $mysqli->query($sql) or die($mysqli->error);
          $checkResult = $user_id;
          $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','login OK','$ip',NOW())";
        } else {
          $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','login Failed','$ip',NOW())";
        }
  
      }else {
		    $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','login Failed','$ip',NOW())";
        $checkResult = -1;
      }
    } else { //比赛账号登录密码不对，Contest不是Special或者被停用，ContestID不存在，或比赛账号和Contest不配对都拒绝登录
      $sql = "SELECT t.`user_id`,t.`password`,t.`contest_id`, c.`title` FROM `contest` as c, `team` as t  
              WHERE c.`contest_id` = t.`contest_id` 
              AND t.`user_id` = '$user_id' 
              AND c.`contest_id`=$cid 
              AND c.`defunct`='N' 
              AND NOT c.`practice` 
              AND c.`user_limit`='Y'";
      $result = $mysqli->query($sql);
      if($result->num_rows == 1){
        $row = $result->fetch_array();
        if(pwCheck($password,$row['password'])){
          $user_id=$row['user_id'];
          $_SESSION['contest_id'] = $row['contest_id'];
          $sql="update team set accesstime=now() where user_id='$user_id' AND contest_id='$cid'";
          $mysqli->query($sql) or die($mysqli->error);
          $checkResult = $user_id;
          $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','team account login OK','$ip',NOW())";
        } else {
          $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','team account login Failed','$ip',NOW())";
        }
      } else $sql="INSERT INTO `loginlog` (user_id,password,ip,`time`)VALUES('$user_id','team account login Failed','$ip',NOW())";
    }
    $result->free();
    @$mysqli->query($sql) or die($mysqli->error); //插入登录日志
    return $checkResult; 
  }
?>