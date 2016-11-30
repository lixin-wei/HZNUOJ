<?php
  /**
   * This file is modified
   * by yybird
   * @2016.03.21
  **/
?>

<?php
require_once("admin-header.php");
require_once("../include/check_get_key.php");
if (!HAS_PRI("edit_contest")) {
	echo "Permission denied!";
	exit(1);
}


$cid=intval($_GET['cid']);
// 	if(!(isset($_SESSION["m$cid"])||HAS_PRI("edit_contest"))) exit();

$sql="select `defunct` FROM `contest` WHERE `contest_id`=$cid";
$result=$mysqli->query($sql);
$num=$result->num_rows;

if ($num<1){
	$result->free();
	echo "No Such Contest!";
	require_once("../oj-footer.php");
	exit(0);
}

$row=$result->fetch_row();
if ($row[0]=='N') $sql="UPDATE `contest` SET `defunct`='Y' WHERE `contest_id`=$cid";
else $sql="UPDATE `contest` SET `defunct`='N' WHERE `contest_id`=$cid";
$result->free();
$mysqli->query($sql);
?>
<script language=javascript>
	history.go(-1);
</script>

