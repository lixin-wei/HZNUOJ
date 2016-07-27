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
  if (!$GE_T){
    echo "<a href='../loginpage.php'>Please Login First!</a>";
    exit(1);
  }
?>
<?php 
  $id=intval($_GET['id']);
  $sql="SELECT `defunct` FROM `news` WHERE `news_id`=$id";
  $result=mysql_query($sql);
  $row=mysql_fetch_row($result);
  $defunct=$row[0];
  echo $defunct;
  mysql_free_result($result);
  if ($defunct=='Y') $sql="update `news` set `defunct`='N' where `news_id`=$id";
  else $sql="update `news` set `defunct`='Y' where `news_id`=$id";
  echo $sql;
  mysql_query($sql) or die(mysql_error());
?>
<script language='javascript'>history.go(-1);</script>
