<?php
  /**
   * This file is modified
   * by yybird
   * @2015.07.08
  **/
?>

<?php 
  require_once("./include/db_info.inc.php");
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
//  echo $user_id."<br>";
  if (get_magic_quotes_gpc ()) {
    $user_id= stripslashes($user_id);
    $password= stripslashes($password);
  }
  $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$mysqli->real_escape_string($user_id)."'";
  $result=$mysqli->query($sql);

  // 比对用户名和密码
  $login=check_login($user_id,$password);

  if ($login) { // 登录成功

    $_SESSION['user_id']=$login;
    echo $mysqli->error;
    while ($result&&$row=$result->fetch_array())
      $_SESSION[$row['rightstr']]=true;
//    $result->free();
/*
    $sql = "SELECT email FROM users WHERE user_id='".$user_id."'";
    $result = $mysqli->query($sql) or die($mysqli->error);
    $row = $result->fetch_array();
    $email = $row[0];
    echo $email;

    // 数据库连接切换至bbs
    require_once("./discuz-api/config.inc.php");
    require_once("./discuz-api/uc_client/client.php");
    // 尝试用该账户进行注册
    $uid = uc_user_register($user_id, $password, $email);
*/
    echo "<script language='javascript'>\n";
    echo "history.go(-2);\n";
    echo "</script>";

  } else {
    echo "<script language='javascript'>\n";
    echo "alert('UserName or Password Wrong!');\n";
    echo "history.go(-1);\n";
    echo "</script>";
  }
?>
