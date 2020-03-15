<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.02
  **/
?>

<?php 
  $cache_time=10;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  $view_title= "Welcome To Online Judge";
  require_once("./include/check_post_key.php");
  require_once("./include/my_func.inc.php");

  //管理员从userinfo界面修改学号、姓名、班级
  if(isset($OJ_NEED_CLASSMODE)&&$OJ_NEED_CLASSMODE){
  if (isset($_POST['admin_mode']) && HAS_PRI("edit_user_profile")) {
      $user_id = $mysqli->real_escape_string(trim($_POST['user_id']));
      if(get_order(get_group($_SESSION['user_id'])) < get_order(get_group($user_id)) //权限比他高
        || $_SESSION['user_id'] == $user_id //自己
      ) {
          $stu_id = $mysqli->real_escape_string(trim($_POST['stu_id']));
          $real_name = $mysqli->real_escape_string(trim($_POST['real_name']));
          $class = $mysqli->real_escape_string(trim($_POST['class']));
          if(class_is_exist($class)){
            $sql = "UPDATE users SET stu_id = '$stu_id', real_name = '$real_name', class = '$class' WHERE user_id = '$user_id'";
            $mysqli->query($sql);
          } 
          echo "<script>window.history.go(-1)</script>";
      }
      else {
          echo "your are not allowed to modify this user!";
      }
      exit(0);
  }
  }
  $err_str="";
  $err_cnt=0;
  $user_id=$_SESSION['user_id'];
  $email=trim($_POST['email']);
  $school=trim($_POST['school']);
  $nick=trim($_POST['nick']);
  $tag_arr = trim($_POST['tag']);
  $tag = "N";
  if ($tag_arr[0] == "o") $tag = "Y";
  else $tag = "N";
  $len = strlen($nick);
  if ($len==0){
    $nick=$user_id;
  } else if(!preg_match("/^[\u{4e00}-\u{9fa5}_a-zA-Z0-9]{1,60}$/", $nick)) { //{1,60} 60=3*20，一个utf-8汉字占3字节
    $err_str=$err_str."输入的{$MSG_NICK}限20个以内的汉字、字母、数字或下划线 ！\\n";
    $err_cnt++;
  }
  $password=$_POST['opassword'];
  $sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_array();
  if ($row && pwCheck($password,$row['password'])) $rows_cnt = 1;
  else $rows_cnt = 0;
  $result->free();
  if ($rows_cnt==0){
    $err_str=$err_str."旧密码错误！\\n";//$err_str=$err_str."Old Password Wrong";
    $err_cnt++;
  }
  $len=strlen($_POST['npassword']);
  if($len>0){
	  if ($len<6 || $len>22){
		$err_str=$err_str."新密码位数要求6-22位！\\n";
		$err_cnt++;
    }else if (strcmp($_POST['npassword'],$_POST['rptpassword'])!=0){
      $err_str=$err_str."两次输入的新密码不一致！\\n";//$err_str=$err_str."Two Passwords Not Same!";
      $err_cnt++;
    }
  }
  if(!preg_match("/^[\u{4e00}-\u{9fa5}a-zA-Z0-9]{0,60}$/", $school)) { //
    $err_str=$err_str."输入的{$MSG_SCHOOL}限20个以内的汉字、字母或数字 ！\\n";
    $err_cnt++;
  }
  $len=strlen($_POST['email']);
  if ($len>100){
    $err_str=$err_str."输入的电子邮箱地址过长！\\n";//$err_str=$err_str."Email Too Long!\\n";
    $err_cnt++;
  }
  if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/", $_POST['email'])) {
	$err_str=$err_str."输入的电子邮箱地址不合法！\\n";//$err_str=$err_str."Email Illegal!\\n";
    $err_cnt++;
  }
  if ($err_cnt>0){
    print "<script language='javascript'>\n";
    echo "alert('";
    echo $err_str;
    print "');\n history.go(-1);\n</script>";
    exit(0);  
  }
  if (strlen($_POST['npassword'])==0) $password=pwGen($_POST['opassword']);
  else $password=pwGen($_POST['npassword']);
  $nick=$mysqli->real_escape_string($nick);
  $school=$mysqli->real_escape_string($school);
  $email=$mysqli->real_escape_string($email);
  $sql="UPDATE `users` SET"
  ."`password`='".($password)."',"
  ."`nick`='".($nick)."',"
  ."`school`='".($school)."',"
  ."`email`='".($email)."' "
  ."WHERE `user_id`='".$mysqli->real_escape_string($user_id)."'"
  ;
  //echo $sql;
  //exit(0);
  $mysqli->query($sql);// or die("Insert Error!\n");
  //header("Location: ./");
?>
<script language=javascript>
  history.go(-2);
</script>