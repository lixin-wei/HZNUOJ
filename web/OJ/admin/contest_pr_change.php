<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<?php require_once("admin-header.php");
require_once("../include/check_get_key.php");
$cid=intval($_GET['cid']);
	if(!(isset($_SESSION["m$cid"])||HAS_PRI("edit_contest"))) exit();
$sql="select `private` FROM `contest` WHERE `contest_id`=$cid";
$result=$mysqli->query($sql);
$num=$result->num_rows;
if ($num<1){
	$result->free();
	echo "No Such Problem!";
	require_once("../oj-footer.php");
	exit(0);
}
$row=$result->fetch_row();
if (intval($row[0])==0) $sql="UPDATE `contest` SET `private`='1' WHERE `contest_id`=$cid";
else $sql="UPDATE `contest` SET `private`='0' WHERE `contest_id`=$cid";
$result->free();
$mysqli->query($sql);
?>
<script language=javascript>
	history.go(-1);
</script>

