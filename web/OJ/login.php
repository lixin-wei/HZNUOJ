<?php
  /**
   * This file is modified
   * by yybird @2016.06.17
   * by D_Star @2016.08.23
  **/
?>

<?php 
  require_once("./include/db_info.inc.php");
  require_once "include/check_post_key.php";
  $vcode=trim($_POST['vcode']);
  if($OJ_VCODE&&($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null) ){
    echo "<script language='javascript'>\n";
    echo "alert('Verify Code Wrong!');\n";
    echo "history.go(-1);\n";
    echo "</script>";
    exit(0);
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

  $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$mysqli->real_escape_string($user_id)."'";
  $result=$mysqli->query($sql);
  // 比对用户名和密码
  $login=check_login($user_id,$password, $cid);
  if ($login == -1) {
    echo "<script>alert('Permission denied! You can only use team account to login this system now!');history.go(-1);</script>";
    exit(0);
  }

  if ($login) { // 登录成功
    //echo $login;
    $_SESSION['user_id']=$login;
    echo $mysqli->error;

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

    $sql = "SELECT email FROM users WHERE user_id='".$user_id."'";
    $result = $mysqli->query($sql) or die($mysqli->error);
    $row = $result->fetch_array();
    $email = $row[0];
//    echo $email;

    // 数据库连接切换至bbs
    //require_once("./discuz-api/config.inc.php");
    //require_once("./discuz-api/uc_client/client.php");
    //$uid = uc_user_register($user_id, $password, $email);

    echo "<script language='javascript'>\n";
    echo "history.go(-2);\n";
    echo "</script>";

  } else {
    echo "<script language='javascript'>\n";
    echo "alert('UserID or Password or ContestID Wrong!');\n";
    echo "history.go(-1);\n";
    echo "</script>";
  }
?>
