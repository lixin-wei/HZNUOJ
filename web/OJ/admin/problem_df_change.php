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
  require_once("../include/my_func.inc.php");
  if (!HAS_PRI("edit_".get_problemset($_GET['id'])."_problem")) {
    require_once("error.php");
    exit(1);
  }
?>
<?php $id=intval($_GET['id']);

$sql="SELECT `defunct` FROM `problem` WHERE `problem_id`=$id";
$result=$mysqli->query($sql);
$row=$result->fetch_row();
$defunct=$row[0];
echo $defunct;
$result->free();
if ($defunct=='Y') $sql="update `problem` set `defunct`='N' where `problem_id`=$id";
else $sql="update `problem` set `defunct`='Y' where `problem_id`=$id";
$mysqli->query($sql) or die($mysqli->error);
?>
<script language=javascript>
	history.go(-1);
</script>
