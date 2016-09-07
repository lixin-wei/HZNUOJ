<?php
  /**
   * This file is modified
   * by yybird @2016.05.25
   * by D_Star @2016.08.23
  **/
?>

<?php @session_start();
  //ini_set("display_errors","On");
  require_once(dirname(__FILE__)."/static.php");
  //if(date('H')<5||date('H')>21||isset($_GET['dark'])) $OJ_CSS="dark.css";
  if (isset($_SESSION['OJ_LANG'])) $OJ_LANG=$_SESSION['OJ_LANG'];

  if($OJ_SAE) {
    $OJ_DATA = "saestor://data/";
    //  for sae.sina.com.cn
    mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT,SAE_MYSQL_USER,SAE_MYSQL_PASS);
    $DB_NAME = SAE_MYSQL_DB;
  } else {
    //for normal install
    if((mysql_connect($DB_HOST,$DB_USER,$DB_PASS)) == null) 
      die('Could not connect: ' . mysql_error());
  }
  // use db
  mysql_query("set names utf8");
  //if(!$OJ_SAE)mysqli_set_charset("utf8");

  if(!mysql_select_db($DB_NAME))
    die('Can\'t use foo : ' . mysql_error());
  //sychronize php and mysql server
  date_default_timezone_set("PRC");
  mysql_query("SET time_zone ='+8:00'");


  // 管理权限
  function HAS_PRI($pri_str){  // if has privilege
    //non-realtime
    //return $_SESSION[$pri_str];
    
    //realtime checking
    $res=mysql_query("SELECT `rightstr` FROM `privilege` WHERE `user_id`='".mysql_real_escape_string($_SESSION['user_id'])."'");
    while($group_name=mysql_fetch_assoc($res)['rightstr']){
      $rs=mysql_query("SELECT * FROM privilege_distribution WHERE group_name='$group_name'");
      $arr=mysql_fetch_assoc($rs);
      if($arr[$pri_str]){
        return true;
      }
    }
    return false;
  }
?>
