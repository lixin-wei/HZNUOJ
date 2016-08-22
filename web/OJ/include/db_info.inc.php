<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php @session_start();
  //ini_set("display_errors","On");
  
  require_once(dirname(__FILE__)."/static.php");
  // 管理权限
  $GE_A = isset($_SESSION['administrator']); // 权限在管理员及以上
  $GE_T = isset($_SESSION['administrator']) || isset($_SESSION['teacher']); // 权限在教师以上
  $GE_TA = isset($_SESSION['administrator']) || isset($_SESSION['teacher']) || isset($_SESSION['teacher_assistant']); // 权限在助教及以上

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

?>
