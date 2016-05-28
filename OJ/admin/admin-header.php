<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.28
  **/
?>

<?php @session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel=stylesheet href='../include/hoj.css' type='text/css'>
<script src="../template/bs3/jquery.min.js"></script>
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script>
  $("document").ready(function (){
    $("form").append("<div id='csrf' />");
    $("#csrf").load("../csrf.php");
  });
</script>
<?php 
  require_once("../include/db_info.inc.php");
  if (!$GE_TA) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
?>
