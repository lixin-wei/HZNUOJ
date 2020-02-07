<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.12
  **/
?>

<?php
  require_once "include/check_post_key.php";
  require_once("./include/db_info.inc.php");
  if(isset($OJ_REGISTER)&&!$OJ_REGISTER) exit(0);
  require_once("./include/my_func.inc.php");
  // OJ 用户名合法性判断
  $err_str="";
  $err_cnt=0;
  $len;
  $user_id=$mysqli->real_escape_string(trim($_POST['user_id']));
  $nick=$mysqli->real_escape_string(trim($_POST['nick']));
  $email=$mysqli->real_escape_string(trim($_POST['email']));
  $school=$mysqli->real_escape_string(trim($_POST['school']));
  $class="其它";
  $stu_id="";
  $real_name="";
  if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){	  
  $class=$mysqli->real_escape_string(trim($_POST['class']));
  $stu_id=$mysqli->real_escape_string(trim($_POST['stu_id']));
  $real_name=$mysqli->real_escape_string(trim($_POST['real_name']));
  }
  $vcode=$mysqli->real_escape_string(trim($_POST['vcode']));
  // echo $user_id.$email.$vcode."<br>";
  // echo $_SESSION["vcode"];
  if($OJ_VCODE&&($vcode!= $_SESSION["vcode"]||$vcode==""||$vcode==null)) { // 验证码错误 
    $_SESSION["vcode"]=null;
    $err_str=$err_str."验证码错误！\\n";//$err_str=$err_str."Verification Code Wrong!\\n";
    $err_cnt++;
  }
  if($OJ_LOGIN_MOD!="hustoj"){
    $err_str=$err_str."System do not allow register.\\n";
    $err_cnt++;
  }
  $len=strlen($user_id);
  if($len<3 || $len>20){
    //$err_str=$err_str."User ID Too Long!\\n";
	//$err_str=$err_str."User ID Too Short!\\n";
	$err_str=$err_str."用户名长度要求3-20个字符！\\n";
    $err_cnt++;
  }
  if (!is_valid_user_name($user_id)){
    $err_str=$err_str."用户名只能包含英文字母和数字！\\n"; //$err_str=$err_str."User ID can only contain NUMBERs & LETTERs!\\n";	
    $err_cnt++;
  }
  $len=strlen($nick);
  if ($len>=30){
    $err_str=$err_str."输入的{$MSG_NICK}过长！\\n";//$err_str=$err_str."Nick Name Too Long!\\n";
    $err_cnt++;
  }else if ($len==0) $nick=$user_id;
  $len = strlen($_POST['password']);
  if ($len<6 || $len>22){
    $err_str=$err_str."密码位数要求6-22位！\\n";
	$err_cnt++;    
  } else if (strcmp($_POST['password'],$_POST['rptpassword'])!=0){    
	  $err_str=$err_str."两次输入的密码不一致！\\n";//$err_str=$err_str."Password Not Same!\\n";
    $err_cnt++;
  }
  $len=strlen($_POST['school']);
  if ($len>100){
	  $err_str=$err_str."输入的就读学校名称过长！\\n";//$err_str=$err_str."School Name Too Long!\\n";   	
    $err_cnt++;
  }
  $len=strlen($_POST['email']);
  if ($len>100){
    $err_str=$err_str."输入的电子邮箱地址过长！\\n";//$err_str=$err_str."Email Too Long!\\n";
    $err_cnt++;
  }
  if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $email)) {
	$err_str=$err_str."输入的电子邮箱地址不合法！\\n";//$err_str=$err_str."Email Illegal!\\n";
    $err_cnt++;
  }
  if ($err_cnt>0){
    print "<script language='javascript'>\n";
    print "alert('";
    print $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);
  }

  // 检查用户是否存在
  $password=pwGen($_POST['password']);
  $sql="SELECT `user_id` FROM `users` WHERE `users`.`user_id` = '".$user_id."'";
  $result=$mysqli->query($sql);
  $rows_cnt=$result->num_rows;
  $result->free();
  if ($rows_cnt == 1){
    print "<script language='javascript'>\n";
    print "alert('用户名已存在，请更换！');\n";//print "alert('User Existed!\\n');\n";
    print "history.go(-1);\n</script>";
    exit(0);
  }
  // 在OJ上注册
  $nick=(htmlentities ($nick,ENT_QUOTES,"UTF-8"));
  $school=(htmlentities ($school,ENT_QUOTES,"UTF-8"));
  $email=(htmlentities ($email,ENT_QUOTES,"UTF-8"));
  $ip=$_SERVER['REMOTE_ADDR'];
  if( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ){
    $REMOTE_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $tmp_ip=explode(',',$REMOTE_ADDR);
    $ip =(htmlentities($tmp_ip[0],ENT_QUOTES,"UTF-8"));
  }
  if(isset($OJ_REG_NEED_CONFIRM)&&$OJ_REG_NEED_CONFIRM) {
	  $defunct="Y";
  } else {
	  $defunct="N";
  }
  $sql="INSERT INTO `users`("
  ."`user_id`,`email`,`defunct`,`ip`,`password`,`reg_time`,`nick`,`school`,class, stu_id, real_name)"
  ."VALUES('".$user_id."','".$email."','".$defunct."','".$_SERVER['REMOTE_ADDR']."','".$password."',NOW(),'".$nick."','".$school."','".$class."','".$stu_id."','".$real_name."')";

  $mysqli->query($sql) or die ($mysqli->error);
  $msg = "注册成功！";
  $sql="INSERT INTO `loginlog` VALUES('$user_id','$password','$ip',NOW())";
  $mysqli->query($sql);
  if(!isset($OJ_REG_NEED_CONFIRM)||!$OJ_REG_NEED_CONFIRM){
    $sql="UPDATE `users` SET `accesstime`=NOW() WHERE `user_id`='$user_id'";
    $mysqli->query($sql);
	  $_SESSION['user_id']=$user_id;
  $sql="SELECT `rightstr` FROM `privilege` WHERE `user_id`='".$_SESSION['user_id']."'";
  //echo $sql."<br />";
  $result=$mysqli->query($sql);
  echo $mysqli->error;
  while ($row=$result->fetch_array()){
    $_SESSION[$row['rightstr']]=true;
    //echo $_SESSION[$row['rightstr']]."<br />";
  }
  $_SESSION['ac']=Array();
  $_SESSION['sub']=Array();
  } else {
	  $msg = $msg."\\n请联系管理员审核通过！";
  }
?>

<script>
	alert("<?php echo $msg ?>");
  window.location.href='index.php';
</script>