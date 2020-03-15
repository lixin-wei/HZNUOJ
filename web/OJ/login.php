<?php
  /**
   * This file is modified
   * by yybird @2016.06.17
   * by D_Star @2016.08.23
  **/
?>

<?php 
  require_once("./include/db_info.inc.php");
  require_once('./include/setlang.php');
  require_once("include/check_post_key.php");
  if($OJ_VCODE){
    $vcode=trim($_POST['vcode']);
    if(($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null) ){
      echo "<script language='javascript'>\n";
      echo "alert('$MSG_VCODE$MSG_Wrong !');\n";
      echo "window.location.href='loginpage.php';";  //echo "history.go(-1);\n";
      echo "</script>";
      exit(0);
    }
  } 
  require_once("./include/login-".$OJ_LOGIN_MOD.".php");
  $user_id=$_POST['user_id'];
  $password=$_POST['password'];
  $cid = $_POST['contest_id'];
//  echo $password;
//  echo $user_id."<br>";
  if (get_magic_quotes_gpc ()) {
    $user_id= stripslashes($user_id);
    $password= stripslashes($password);
  }

  
  // 比对用户名和密码
  $login=check_login($user_id,$password, $cid);
  if ($login == -1) {
    echo "<script>alert('Permission denied! You can only use team account to login this system now!');history.go(-1);</script>";
    exit(0);
  }

  if ($login) { // 登录成功
    //echo $login;
    $_SESSION['user_id']=$login;
	  $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$mysqli->real_escape_string($user_id)."'";
    $result=$mysqli->query($sql);
    echo $mysqli->error;
    while ($row=$result->fetch_array()){
      //登录时把权限写入session,私有比赛用户可以免密码进入比赛
		  $_SESSION[$row['rightstr']]=true;
	  }
    //set privileges (for non-realtime privilege check)
    while($group_name=$result->fetch_array()['rightstr']){
      $rs=$mysqli->query("SELECT * FROM privilege_distribution WHERE group_name='$group_name'");
      $arr=$rs->fetch_array();
      //print_r($arr);
      foreach ($arr as $key => $value) {
        if($key!="group_name"){
          $_SESSION[$key]=$value;
        }
      }
    }
    $result->free();
	
//    $sql = "SELECT email FROM users WHERE user_id='".$user_id."'";
//    $result = $mysqli->query($sql) or die($mysqli->error);
//    $row = $result->fetch_array();
//    $email = $row[0];
//    echo $email;

    // 数据库连接切换至bbs
    //require_once("./discuz-api/config.inc.php");
    //require_once("./discuz-api/uc_client/client.php");
    //$uid = uc_user_register($user_id, $password, $email);
    if($cid){
      $url="contest.php?cid=$cid";      
    } else $url="userinfo.php?user=$user_id";
    echo "<script language='javascript'>\n";
    echo "window.location.href='$url';";  //echo "history.go(-2);\n";
    echo "</script>";

  } else {
    echo "<script language='javascript'>\n";
    echo "alert('$MSG_LoginError');\n";
	  echo "window.location.href='loginpage.php';";  //echo "history.go(-1);\n";
    echo "</script>";
  }
?>
