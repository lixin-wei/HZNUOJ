<?php
  /**
   * This file is modified
   * by yybird
   * @2016.05.25
  **/
?>

<?php 
  require_once("admin-header.php");
  require_once("../include/check_get_key.php");
  if (!HAS_PRI("edit_news")) {
    require_once("error.php");
    exit(1);
  }
?>
<?php 
  $id=intval($_GET['id']);
  $sql="SELECT `defunct` FROM `news` WHERE `news_id`=$id";
  $result=$mysqli->query($sql);
  $row=$result->fetch_row();
  $defunct=$row[0];
  //echo $defunct;
  $result->free();
  if ($defunct=='Y') $sql="update `news` set `defunct`='N' where `news_id`=$id";
  else $sql="update `news` set `defunct`='Y' where `news_id`=$id";
  //echo $sql;
  $mysqli->query($sql) or die($mysqli->error);
?>
<script language='javascript'>history.go(-1);</script>
