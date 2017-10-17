<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.26
  **/
?>

<?php $cache_time=10;
  $OJ_CACHE_SHARE=false;
  require_once('./include/cache_start.php');
  require_once('./include/db_info.inc.php');
  require_once('./include/setlang.php');
  require_once('./include/classList.inc.php');
  $view_title= "Welcome To Online Judge";
  if (!isset($_SESSION['user_id'])){
    $view_errors= "<a href=./loginpage.php>$MSG_Login</a>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
  if (isset($_SESSION['contest_id'])){
    $view_errors= "<font color='red'>Team account can not use this page!</font>";
    require("template/".$OJ_TEMPLATE."/error.php");
    exit(0);
  }
  $sql="SELECT * FROM `users` WHERE `user_id`='".$_SESSION['user_id']."'";
  $result=$mysqli->query($sql);
  $row=$result->fetch_object();
  $result->free();
  
  /////////////////////////Template
  require("template/".$OJ_TEMPLATE."/modifypage.php");
  /////////////////////////Common foot
  if(file_exists('./include/cache_end.php'))
    require_once('./include/cache_end.php');
?>
