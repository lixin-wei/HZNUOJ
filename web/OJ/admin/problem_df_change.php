<?php
  /**
   * This file is modified
   * by yybird
   * @2016.06.27
  **/
?>

<?php 
  require_once("admin-header.php");
  require_once("../include/check_get_key.php");
  require_once("../include/check_get_key.php");
  if (!HAS_PRI("edit_".get_problemset($_GET['id'])."_problem")) {
    require_once("error.php");
    exit(1);
  }
?>
<?php $id=intval($_GET['id']);
$sql="SELECT `defunct` FROM `problem` WHERE `problem_id`=$id";
$result=mysql_query($sql);
$row=mysql_fetch_row($result);
$defunct=$row[0];
echo $defunct;
mysql_free_result($result);
if ($defunct=='Y') $sql="update `problem` set `defunct`='N' where `problem_id`=$id";
else $sql="update `problem` set `defunct`='Y' where `problem_id`=$id";
mysql_query($sql) or die(mysql_error());
?>
<script language=javascript>
	history.go(-1);
</script>
