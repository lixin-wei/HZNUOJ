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


  $err_str="";
  $err_cnt=0;
  $user_id=$_SESSION['user_id'];
  $stu_id = trim($_POST['stu_id']);
  $email=trim($_POST['email']);
  $school=trim($_POST['school']);
  $class=trim($_POST['class']);
  $real_name=trim($_POST['real_name']);
  $nick=trim($_POST['nick']);
  $tag_arr = trim($_POST['tag']);
  $tag = "N";
  if ($tag_arr[0] == "o") $tag = "Y";
  else $tag = "N";
  $len = strlen($nick);
  if ($len>70){
    $err_str=$err_str."Nick Name Too Long!";
    $err_cnt++;
  }else if ($len==0) $nick=$user_id;
  $password=$_POST['opassword'];
  $sql="SELECT `user_id`,`password` FROM `users` WHERE `user_id`='".$user_id."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_array();
  if ($row && pwCheck($password,$row['password'])) $rows_cnt = 1;
  else $rows_cnt = 0;
  $result->free();
  if ($rows_cnt==0){
    $err_str=$err_str."Old Password Wrong";
    $err_cnt++;
  }
  $len=strlen($_POST['npassword']);
  if ($len<6 && $len>0){
    $err_cnt++;
    $err_str=$err_str."Password should be Longer than 6!\\n";
  }else if (strcmp($_POST['npassword'],$_POST['rptpassword'])!=0){
    $err_str=$err_str."Two Passwords Not Same!";
    $err_cnt++;
  }
  $len=strlen($_POST['school']);
  if ($len>100){
    $err_str=$err_str."School Name Too Long!";
    $err_cnt++;
  }
  $len=strlen($_POST['email']);
  if ($len>100){
    $err_str=$err_str."Email Too Long!";
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
  echo $class;
  $sql="UPDATE `users` SET"
  ."`password`='".($password)."',"
  ."`nick`='".($nick)."',"
  ."`school`='".($school)."',"
  ."`class`='".($class)."', "
  ."`stu_id`='".($stu_id)."',"
  ."`real_name`='".($real_name)."', "
  ."`email`='".($email)."' "
  ."WHERE `user_id`='".$mysqli->real_escape_string($user_id)."'"
  ;
  //echo $sql;
  //exit(0);
  $mysqli->query($sql);// or die("Insert Error!\n");
  header("Location: ./");
?>
