<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.28
  **/
?>

<?php @session_start();?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--
<link rel=stylesheet href='../include/hoj.css' type='text/css'>
-->
<script src="../template/bs3/jquery.min.js"></script>
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">

<!-- jQuery文件。务必在bootstrap.min.js 之前引入 -->
<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>

<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
  $("document").ready(function (){
    $("form").append("<div id='csrf' />");
    $("#csrf").load("../csrf.php");
  });
</script>
<?php 
  require_once("../include/db_info.inc.php");
  if (!HAS_PRI('enter_admin_page')) {
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
?>
